<?php
/* @var $this ModelController */
/* @var $title string */
/* @var $model object */
/* @var $data array */

$this->pageTitle=$title;

$buttons = array();
$buttons[] = array(
    'type'=>'primary',
    'label'=>Yii::t('YcmModule.ycm',
            'Create {name}',
            array('{name}'=>$this->module->getSingularName($model))
        ),
    'url'=>$this->createUrl('model/create',array('name'=>get_class($model))),
);

if(isset($model->orderField)){
    $buttons[] = array(
        'type'=>'sortable',
        'label'=>Yii::t('YcmModule.ycm', 'Change order'),
        'url'=>'#',
        'htmlOptions' => array('class'=>'sortable'),
    );
}
?>

<div class="btn-toolbar">
	<?php
	if ($this->module->getHideCreate($model) === false) {
		$this->widget('bootstrap.widgets.TbButtonGroup',array(
			'type'=>'',
			'buttons'=>$buttons,
		));
	}
	?>
</div>
<?php $this->widget('bootstrap.widgets.TbGridView',$data); ?>

<?php if(isset($model->orderField)): ?>
<script>
    jQuery(document).on('click','.btn.sortable',function() {
        if($(this).hasClass('active')){
            $(this).removeClass('active');
            $('#objects-grid table.items tbody').sortable( 'destroy' );
            $('#objects-grid table.items tbody').unbind();
        }else{
            $(this).addClass('active');
            installSortable();
        }

        return false;
    });

    function installSortable() {
        var fixHelper = function(e, ui) {
            ui.children().each(function() {
                $(this).width($(this).width());
            });
            return ui;
        };

        $('#objects-grid table.items tbody').sortable({
            forcePlaceholderSize: true,
            forceHelperSize: true,
            items: 'tr',
            update : function () {
                var serial = $('#objects-grid table.items tbody').sortable('serialize', {key: 'items[]', attribute: 'class', expression: /[_](\d+)/ });

                serial = serial + '&model=' + '<?php echo get_class($model);?>';
                serial = serial + '&field=' + '<?php echo $model->orderField;?>';

                $.ajax({
                    'url': '<?php echo $this->createUrl('//ycm/ajax/sort')?>',
                    'type': 'post',
                    'data': serial,
                    'success': function(data){
                        jQuery('#objects-grid').yiiGridView('update');
                    },
                    'error': function(request, status, error){
                        alert('We are unable to set the sort order at this time.  Please try again in a few minutes.');
                        jQuery('#objects-grid').yiiGridView('update');
                    }
                });
            },
            helper: fixHelper
        }).disableSelection();
    }

    function reInstallSortable(id, data) {
        if($('.btn.sortable').hasClass('active')){
            installSortable();
        }
    }
</script>
<?php endif;?>
