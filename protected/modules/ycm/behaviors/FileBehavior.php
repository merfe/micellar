<?php

/**
 * Class FileBehavior
 *
 * @uses CModelBehavior
 * @copyright 2012-2014
 * @author Jani Mikkonen <janisto@php.net>
 * @license public domain
 */
class FileBehavior extends CModelBehavior
{
	/**
	 * @var string $uploadPath upload path
	 */
	public $uploadPath;

	/**
	 * @var string $uploadUrl upload URL
	 */
	public $uploadUrl;

	/**
	 * Get file path.
	 *
	 * @param string $attribute Model attribute
	 * @return string Model attribute file path
	 */
	public function getFilePath($attribute, $version = false)
	{
		if ($this->owner->hasAttribute($attribute) && !empty($this->owner->$attribute)) {
			$file = $this->owner->$attribute;
            if($version) $file = $this->generateVersionName($file, $version);
			$name = get_class($this->owner);
			$path = $this->getUploadPath();
			return $path.DIRECTORY_SEPARATOR.$file; //my fix
		}
		return false;
	}

	/**
	 * Get relative file URL.
	 *
	 * @param string $attribute Model attribute
	 * @return string Model attribute relative file URL
	 */
	public function getFileUrl($attribute, $version = false)
	{
		if ($this->owner->hasAttribute($attribute) && !empty($this->owner->$attribute)) {
			$file = $this->owner->$attribute;
            if($version) $file = $this->generateVersionName($file, $version);
			$name = get_class($this->owner);
			return $this->getUploadUrl().'/'.$file; //my fix
		}
		return false;
	}

	/**
	 * Get absolute file URL.
	 *
	 * @param string $attribute Model attribute
	 * @return string Model attribute absolute file URL
	 */
	public function getAbsoluteFileUrl($attribute, $version = false)
	{
		$url = $this->getFileUrl($attribute, $version);
		if ($url) {
			if (strpos($url, '//') === false) {
				return Yii::app()->getRequest()->getHostInfo().$url;
			} else {
				return $url;
			}
		}
		return false;
	}

	/**
	 * @return string upload path
	 */
	protected function getUploadPath()
	{
		if ($this->uploadPath === null) {
			$this->uploadPath = Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'uploads';
		}
		return $this->uploadPath;
	}

	/**
	 * @return string upload URL
	 */
	protected function getUploadUrl()
	{
		if ($this->uploadUrl === null) {
			$this->uploadUrl = Yii::app()->request->baseUrl .'/uploads';
		}
		return $this->uploadUrl;
	}

    protected function generateVersionName($file, $version)
    {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $basename = pathinfo($file, PATHINFO_FILENAME);

        return $basename.$version.'.'.$extension;
    }
}
