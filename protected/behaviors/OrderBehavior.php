<?php

class OrderBehavior extends CActiveRecordBehavior
{

    protected $_old_sort_order;
    protected $_max_order;
    protected $_exist;

    public $sort_order_field = 'sort_order';
    public $criteria;

    public function afterSave($event)
    {
        $owner = $this->owner;

        $transaction = Yii::app()->getDb()->beginTransaction();
        try {

            if ($owner->isNewRecord) {
                if (!is_numeric($owner->{$this->sort_order_field})) {
                    $owner->{$this->sort_order_field} = $this->getMaxOrder() + 1;
                } else {
                    if ($this->getIsExist()) {

                        $criteria = $this->getBaseCriteria();
                        $criteria->addCondition($this->sort_order_field . ' >= :sort_order');
                        $criteria->params[':sort_order'] = $owner->{$this->sort_order_field};

                        $owner::model()->updateAll(
                            array(
                                $this->sort_order_field => new CDbExpression(
                                        $this->sort_order_field . ' + :counter',
                                        array(':counter' => 1)
                                    )
                            ),
                            $criteria
                        );

                    } else {
                        if($owner->{$this->sort_order_field} > 0 AND $owner->{$this->sort_order_field} < $this->getMaxOrder()){

                        }else{
                            $owner->{$this->sort_order_field} = $this->getMaxOrder() + 1;
                        }
                    }

                }

            } else {
                if (!is_numeric($owner->{$this->sort_order_field})) {
                    $owner->{$this->sort_order_field} = $this->_old_sort_order;
                } else {
                    if ($this->_old_sort_order != $owner->{$this->sort_order_field}) {
                        $freePlace = false;
                        if ($this->getIsExist()) {
                            if(!is_numeric($this->_old_sort_order)){
                                $this->_old_sort_order = $this->getMaxOrder() + 1;
                            }
                        } else {
                            if($owner->{$this->sort_order_field} > 0 AND $owner->{$this->sort_order_field} < $this->getMaxOrder()){
                                $freePlace = true;
                            }else{
                                if(!is_numeric($this->_old_sort_order)){
                                    $this->_old_sort_order = $this->getMaxOrder() + 1;
                                    $owner->{$this->sort_order_field} = $this->getMaxOrder() + 1;
                                }else{
                                    $owner->{$this->sort_order_field} = $this->getMaxOrder();
                                }

                            }
                        }

                        if(!$freePlace){
                            $sign = '+';

                            if ($this->_old_sort_order >= $owner->{$this->sort_order_field}) {

                                $criteria = $this->getBaseCriteria();
                                $criteria->addCondition($this->sort_order_field . ' >= :sort_order');
                                $criteria->params[':sort_order'] = $owner->{$this->sort_order_field};
                                $criteria->addCondition($this->sort_order_field . ' < :old_sort_order');
                                $criteria->params[':old_sort_order'] = $this->_old_sort_order;
                            } else {
                                $sign = '-';

                                $criteria = $this->getBaseCriteria();
                                $criteria->addCondition($this->sort_order_field . ' <= :sort_order');
                                $criteria->params[':sort_order'] = $owner->{$this->sort_order_field};

                                $criteria->addCondition($this->sort_order_field . ' > :old_sort_order');
                                $criteria->params[':old_sort_order'] = $this->_old_sort_order;
                            }

                            $owner::model()->updateAll(
                                array(
                                    $this->sort_order_field => new CDbExpression(
                                            $this->sort_order_field . ' ' . $sign . ' :counter',
                                            array(':counter' => 1)
                                        )
                                ),
                                $criteria
                            );
                        }
                    }
                }
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
        }

    }

    public function afterDelete($event)
    {
        $owner = $this->owner;

        $sort_order = $owner->{$this->sort_order_field};

        $criteria = $this->getBaseCriteria();
        $criteria->addCondition($this->sort_order_field . ' > :sort_order');
        $criteria->params[':sort_order'] = $sort_order;

        $owner::model()->updateAll(
            array(
                $this->sort_order_field => new CDbExpression(
                        $this->sort_order_field . ' - :counter',
                        array(':counter' => 1)
                    )
            ),
            $criteria
        );

    }

    public function afterFind($event)
    {
        $owner = $this->owner;

        $this->_old_sort_order = $owner->{$this->sort_order_field};
    }

    protected function getBaseCriteria($addPkCondition = true)
    {
        $owner = $this->owner;

        $criteria = new CDbCriteria();
        $criteria->order = $this->sort_order_field;

        if($addPkCondition){
            $criteria->addCondition($owner::model()->tableSchema->primaryKey . ' <> '. $owner->getPrimaryKey());
        }

        if($this->criteria){
            $criteria->mergeWith($this->criteria);
        }

        return $criteria;
    }

    protected function getMaxOrder()
    {
        if(isset($this->_max_order))
            return $this->_max_order;

        $owner = $this->owner;

        $tableSchema = $owner::model()->getTableSchema();
        $command = $owner::model()->getCommandBuilder()->createFindCommand($tableSchema, $this->getBaseCriteria(false));
        $this->_max_order = (int) $command
            ->select('max(' . $this->sort_order_field . ')')
            ->queryScalar();

        return $this->_max_order;
    }

    protected function getIsExist()
    {
        if(isset($this->_exist))
            return $this->_exist;

        $owner = $this->owner;
        $exist = false;
        if (is_numeric($owner->{$this->sort_order_field})) {
            $criteria = $this->getBaseCriteria(false);
            $criteria->addCondition($this->sort_order_field . ' = '. $owner->{$this->sort_order_field});

            $exist = $owner::model()->find($criteria);

            if (is_null($exist))
                $exist = false;
        }

        return $this->_exist = $exist;
    }

}