<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout = 'main';

    public $breadcrumbs = array();
    public $pageTitle = 'Micellar';
    public $pageTitleSeparator = ' - ';

    public function init()
    {
        parent::init();
    }

	public function mainMenu()
	{
		return array(
			'home' => 'Главная',
			'projects' => 'Проекты',
			'services' => 'Услуги',
			'company' => 'Компания',
			'contacts' => 'Контакты',
		);
	}
	
	function isMobile() 
	{
	    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}

    public function addPageTitlePart($part)
    {
        $this->pageTitle .= $this->pageTitleSeparator . $part;
    }

}