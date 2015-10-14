<?php
/* @var $this DefaultController */
/* @var $models array */

$this->pageTitle=Yii::t('YcmModule.ycm','Administration');
?>

<?php $this->beginWidget('bootstrap.widgets.TbHeroUnit'); ?>
<?php foreach ($models as $menu => $list): ?>
    <h2><?php echo $menu; ?></h2>
	<?php foreach ($list as $model): ?>
		<div class="btn-toolbar">
			<?php
			$buttons=array();
			$download=false;
			$downloadItems=array();

			array_push($buttons,array(
				'type'=>'primary',
				'label'=>$this->module->getAdminName($model),
				'url'=>$this->createUrl('model/list',array('name'=>$model)),
			));
			if ($this->module->getHideCreate($model) === false) {
				array_push($buttons,array(
					'label'=>Yii::t('YcmModule.ycm','Create'),
					'url'=>$this->createUrl('model/create',array('name'=>$model)),
				));
			}
			array_push($buttons,array(
				'label'=>Yii::t('YcmModule.ycm','List'),
				'url'=>$this->createUrl('model/list',array('name'=>$model)),
			));

			if ($this->module->getDownloadExcel($model)) {
				$download=true;
				array_push($downloadItems,array(
					'label'=>Yii::t('YcmModule.ycm','Excel'),
					'url'=>$this->createUrl('model/excel',array('name'=>$model)),
				));
			}
			if ($this->module->getDownloadMsCsv($model)) {
				$download=true;
				array_push($downloadItems,array(
					'label'=>Yii::t('YcmModule.ycm','MS CSV'),
					'url'=>$this->createUrl('model/mscsv',array('name'=>$model)),
				));
			}
			if ($this->module->getDownloadCsv($model)) {
				$download=true;
				array_push($downloadItems,array(
					'label'=>Yii::t('YcmModule.ycm','CSV'),
					'url'=>$this->createUrl('model/csv',array('name'=>$model)),
				));
			}

			$this->widget('bootstrap.widgets.TbButtonGroup',array(
				'type'=>'', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
				'buttons'=>$buttons,
			));

			if ($download) {
				$this->widget('bootstrap.widgets.TbButtonGroup',array(
					'type'=>'',
					'buttons'=>array(
						array('label'=>Yii::t('YcmModule.ycm',
							'Download {name}',
							array('{name}'=>$this->module->getPluralName($model))
						)),
						array('items'=>$downloadItems),
					),
				));
			}
			?>
		</div>
	<?php endforeach; ?>
<?php endforeach; ?>
<?php $this->endWidget(); ?>