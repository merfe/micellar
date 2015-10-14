<?php

class CacheCommand extends CConsoleCommand
{

    public function actionClear()
    {
        Yii::app()->cache->flush();
    }

}
