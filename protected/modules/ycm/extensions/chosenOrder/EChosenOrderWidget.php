<?php

class EChosenOrderWidget extends CWidget
{
	const PACKAGE_ID = 'chosen-order-widget';

	protected $assetsPath;

	protected $assetsUrl;

	public $selector = '.chosen-order-select';
	public $selected = '[]';

	public function init()
	{
		parent::init();
		if ($this->assetsPath === null) {
			$this->assetsPath = dirname(__FILE__).DIRECTORY_SEPARATOR.'assets';
		}
		if ($this->assetsUrl === null) {
			$this->assetsUrl = Yii::app()->assetManager->publish($this->assetsPath);
		}
		$this->registerClientScript();
	}

	protected function registerClientScript()
	{
		$cs = Yii::app()->clientScript;
		if (!isset($cs->packages[self::PACKAGE_ID])) {
			$cs->packages[self::PACKAGE_ID] = array(
				'basePath' => $this->assetsPath,
				'baseUrl' => $this->assetsUrl,
				'js' => array(
					'js/jquery.multi-select.js',
					'js/jquery.quicksearch.js',
				),
				'css' => array(
					'css/multi-select.css'
				),
                'img' => array(
                    'img/switch.png'
                ),
				'depends' => array(
					'jquery',
				),
			);
		}
		$cs->registerPackage(self::PACKAGE_ID);

		$cs->registerScript(
			__CLASS__ . '#' . $this->id,
"var initArr = $this->selected;
var arr = initArr.slice(0);
$('$this->selector').multiSelect({
selectableHeader: \"<input type='text' class='search-input' autocomplete='off' style='margin-bottom: 20px;'>\",
selectionHeader: \"<input type='text' class='search-input' autocomplete='off'' style='margin-bottom: 20px;'>\",
keepOrder: true,
afterSelect: function(values){
values.forEach(function(value){
arr.push(value);
});
$('#chosenOrderHidden').val(arr);
    this.qs1.cache();
    this.qs2.cache();
},
afterDeselect: function(values){
values.forEach(function(value){
arr.splice( $.inArray( value, arr ), 1 );
});
$('#chosenOrderHidden').val(arr);
    this.qs1.cache();
    this.qs2.cache();
},
afterInit: function(ms){
    var that = this,
        \$selectableSearch = that.\$selectableUl.prev(),
        \$selectionSearch = that.\$selectionUl.prev(),
        selectableSearchString = '#'+that.\$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
        selectionSearchString = '#'+that.\$container.attr('id')+' .ms-elem-selection.ms-selected';

    that.qs1 = \$selectableSearch.quicksearch(selectableSearchString)
    .on('keydown', function(e){
      if (e.which === 40){
        that.\$selectableUl.focus();
        return false;
      }
    });

    that.qs2 = \$selectionSearch.quicksearch(selectionSearchString)
    .on('keydown', function(e){
      if (e.which == 40){
        that.\$selectionUl.focus();
        return false;
      }
    });
$('$this->selector').multiSelect('deselect_all')
initArr.forEach(function(value){
$('$this->selector').multiSelect('select', value);
});
$('#chosenOrderHidden').val(arr);
}
});",
			CClientScript::POS_READY
		);

	}
}