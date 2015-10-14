<?php

class AjaxController extends AdminController
{
    public function actionSort()
    {
        if (!Yii::app()->request->isAjaxRequest)
            Yii::app()->end();

        $model = Yii::app()->request->getPost('model');
        $field = Yii::app()->request->getPost('field');
        $items = Yii::app()->request->getPost('items');

        $update = array();

        if (is_array($items) && $model && $field) {
            $alias = $model::model()->tableName();

            $cur_items = $model::model()->resetScope()->findAllByPk($items, array('alias' => $alias,'order'=>$alias.'.'.$field));

            for ($i = 0; $i < count($items); $i++) {
                if ($items[$i] != $cur_items[$i]->getPrimaryKey()) {
                    $update[$items[$i]] = $cur_items[$i]->$field;
                }
            }
        }

        if($update){

            $pkName = $model::model()->tableSchema->primaryKey;

            $expression = "CASE ";
            foreach($update as $key => $value){
                $expression .= "WHEN `$pkName` = $key THEN '$value' ";
            }
            $expression .= 'END';
            $transaction = Yii::app()->getDb()->beginTransaction();
            try {
                $model::model()->updateByPk(array_keys($update),
                    array($field => new CDbExpression($expression))
                );

                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollback();
            }
        }

    }


    public function actionUpdateField()
    {
        if (!Yii::app()->request->isAjaxRequest)
            Yii::app()->end();

        $model = Yii::app()->request->getPost('model');
        $field = Yii::app()->request->getPost('name');
        $pk = (int) Yii::app()->request->getPost('pk');
        $value = Yii::app()->request->getPost('value');
        $scenario = Yii::app()->request->getPost('scenario');

        $record = $model::model()->findByPk($pk);

        $record->$field = $value;
        $record->setScenario($scenario);
        $record->save(true, array($field));

    }
}
