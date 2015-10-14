<?php
/* @var $this AdminController */

$cs=Yii::app()->clientScript;
$baseUrl=$this->module->assetsUrl;
$cs->registerCoreScript('jquery');
$cs->registerCssFile($baseUrl.'/css/styles.css');
?>
<!DOCTYPE html>
<html lang="<?php echo Yii::app()->language; ?>">
<head>
	<meta charset="utf-8">
	<meta name="robots" content="NONE,NOARCHIVE" />
	<title><?php echo $this->pageTitle; ?></title>
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
<?php
$models = $this->module->modelsList;

$items = array();

foreach($models as $menu => $list):
    $subItems = array();
    foreach ($list as $model):
        $subItems[] =
                array(
                    'label'=>$this->module->getAdminName($model),
                    'url'=>$this->createUrl('model/list',array('name'=>$model)),
                    'visible'=>!Yii::app()->user->isGuest,
        );
    endforeach;
    $items[] = array(
        'class'=>'bootstrap.widgets.TbMenu',
        'items'=>array(
            array(
                'label'=>Yii::t('YcmModule.ycm',$menu),
                'visible'=>!Yii::app()->user->isGuest,
                'items'=>$subItems
            ),

        ),
    );
endforeach;
$items[] = array(
    'class' => 'bootstrap.widgets.TbMenu',
    'items' => array(
        array(
            'label' => Yii::t('YcmModule.ycm', 'Statistics'),
            'url' => array('/' . $this->module->name . '/default/stats'),
            'visible' => !Yii::app()->user->isGuest,
        ),
    ),
);
$items[] = array(

    'class'=>'bootstrap.widgets.TbMenu',
    'htmlOptions'=>array('class'=>'pull-right'),
    'items'=>array(
        array(
            'label'=>Yii::t('YcmModule.ycm','Login'),
            'url'=>array('/'.$this->module->name.'/default/login'),
            'visible'=>Yii::app()->user->isGuest,
        ),
        array(
            'label'=>Yii::t('YcmModule.ycm','Logout'),
            'url'=>array('/'.$this->module->name.'/default/logout'),
            'visible'=>!Yii::app()->user->isGuest,
        ),
    ),
);
$this->widget('bootstrap.widgets.TbNavbar',array(
	'type'=>'inverse', // null or 'inverse'
	'brand'=>Yii::t('YcmModule.ycm','Administration'),
	'brandUrl'=>Yii::app()->createUrl('/'.$this->module->name),
	'collapse'=>true, // requires bootstrap-responsive.css
	'items'=>$items,
));
?>

<?php if (!empty($this->breadcrumbs)):?>
<div class="container-fluid">
	<?php $this->widget('bootstrap.widgets.TbBreadcrumbs',array(
		'links'=>$this->breadcrumbs,
		'separator'=>'/',
		'homeLink'=>CHtml::link(Yii::t('YcmModule.ycm','Home'),Yii::app()->createUrl('/'.$this->module->name)),
	)); ?>
</div>
<?php endif?>

<div class="container-fluid">
	<?php $this->widget('bootstrap.widgets.TbAlert',array(
		'block'=>true,
		'fade'=>true,
		'closeText'=>'&times;',
	)); ?>

	<?php echo $content; ?>

</div>
</body>
</html>