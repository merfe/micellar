<?php

class ImageBehavior extends CModelBehavior
{

	public function getFileUrl($attribute)
	{
		if ($this->owner->hasAttribute($attribute) && !empty($this->owner->$attribute)) {
			$file=$this->owner->$attribute;
            return $file;
		}
		return false;
	}

	public function getAbsoluteFileUrl($attribute)
	{
		$url=$this->getFileUrl($attribute);
		if ($url) {
			return Yii::app()->getRequest()->getHostInfo().$url;
		}
		return false;
	}

}