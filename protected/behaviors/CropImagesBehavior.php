<?php

class CropImagesBehavior extends CActiveRecordBehavior {

    protected $_oldImages = array();

    public $jpegQuality = 75;
    public $toFormat = false;
    public $ratio;

    public function afterSave($event)
    {
        $caller = $this->owner;

        if(!isset($caller->_imagesSetting))
            return false;

        $attributePath = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . 'uploads';

        $to_update = array();

        foreach($caller->_imagesSetting as $field => $data){

            $versions = isset($data['versions']) ? $data['versions'] : array();
            $options = isset($data['options']) ? $data['options'] : array();

            $fileName = $caller->$field;
            $path = $attributePath . DIRECTORY_SEPARATOR . $fileName;

            $extension = pathinfo($fileName, PATHINFO_EXTENSION);

            $rewriteBase = array_key_exists('', $versions);

            if(isset($this->_oldImages[$field]) AND $this->_oldImages[$field] == $fileName){
                unset($this->_oldImages[$field]);
                continue;
            }

            if(!file_exists($path))
                continue;

            /* @var $image CImageHandler*/
            try{
                $image = Yii::app()->ih->load($path);
            }catch (Exception $e){
                continue;
            }

            $toFormat = isset($options['toFormat']) ? $options['toFormat'] : $this->toFormat;
            $globalRatio = isset($options['ratio']) ? $options['ratio'] : $this->ratio;
            $globalJpegQuality = isset($options['jpegQuality']) ? $options['jpegQuality'] : $this->jpegQuality;

            if($toFormat){
                switch ($toFormat)
                {
                    case CImageHandler::IMG_GIF:
                        $extension = 'gif';
                        break;
                    case CImageHandler::IMG_JPEG:
                        $extension = 'jpg';
                        break;
                    case CImageHandler::IMG_PNG:
                        $extension = 'png';
                        break;
                    default:
                        throw new Exception('Invalid image format for save');
                }
            }

            $basename = pathinfo($fileName, PATHINFO_FILENAME);
            $changeFormat = ($toFormat AND ($image->getFormat() != $toFormat));

            if($rewriteBase OR $changeFormat){
                $basename = trim(md5($field.time().uniqid(rand(),true)));
                @unlink($path);
                $to_update[$field] = $basename. '.' . $extension;

                if(!$rewriteBase AND $changeFormat){
                    $versions[''] = array();
                }
            }

            foreach($versions as $postfix => $settings){
                $image->reload();
                $width = isset($settings['width']) ? $settings['width'] : null;
                $height = isset($settings['height']) ? $settings['height'] : null;
                $ratio = isset($settings['ratio']) ? $settings['ratio'] : $globalRatio;
                $jpegQuality = isset($settings['jpegQuality']) ? $settings['jpegQuality'] : $globalJpegQuality;
                $new_path = $attributePath . DIRECTORY_SEPARATOR . $basename. $postfix . '.' . $extension;
                if (!file_exists($new_path)) {
                    $crop = true;
                    if($width !== null){
                        $scale =  $width/$image->getWidth();
                        $height = $image->getHeight()*$scale;
                        if($ratio !== null){
                            $height = $width / $ratio;
                        }
                        $toWidth = max($width,$height / $image->getHeight() * $image->getWidth());
                        $image->resize($toWidth, $toWidth / $image->getWidth() * $image->getHeight(), false);
                    }elseif($height !== null){
                        $scale = $height/$image->getHeight();
                        $width = $image->getWidth()*$scale;
                        if($ratio !== null){
                            $width = $width / $ratio;
                        }
                        $toHeight = max($height, $height / $image->getWidth() * $image->getHeight());
                        $image->resize($toHeight / $image->getHeight() * $image->getWidth(), $toHeight, false);
                    }else{
                        $crop = false;
                    }

                    if($crop){
                        $image->crop($width, $height, 0, 0);
                    }

                    $image->save($new_path, $toFormat, $jpegQuality);
                }
            }
        }

        if($to_update)
            $caller->updateByPk($caller->getPrimaryKey(),$to_update);

        $this->afterDelete($event);
    }

    public function afterDelete($event)
    {
        $caller = $this->owner;

        if(!isset($caller->_imagesSetting))
            return false;

        $attributePath = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . 'uploads';

        foreach($caller->_imagesSetting as $field => $data){

            $versions = isset($data['versions']) ? $data['versions'] : array();

            if(!isset($this->_oldImages[$field]))
                continue;
            $fileName = $this->_oldImages[$field];
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            $basename = pathinfo($fileName, PATHINFO_FILENAME);

            //force remove original
            if (!array_key_exists('', $versions)){
                $versions[''] = array();
            }

            foreach($versions as $postfix => $settings){
                $new_path = $attributePath . DIRECTORY_SEPARATOR . $basename. $postfix . '.' . $extension;
                if (file_exists($new_path))  @unlink($new_path);
            }
        }
    }

    public function afterFind($event)
    {
        $caller = $this->owner;

        if(!isset($caller->_imagesSetting))
            return false;

        foreach($caller->_imagesSetting as $field => $data){
            $fileName = $caller->$field;
            $this->_oldImages[$field] = $fileName;
        }

    }

}
