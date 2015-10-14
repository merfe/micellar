<?php

class MainMenuWidget extends CWidget{
	
    public function init() 
    {
    }

    public function run()
    {
    	$menu = (object) array(
			'link1' => 'Уход за волосами',
			'link2' => 'Уход за лицом',
			'link3' => 'Уход за телом',
			'link4' => 'Для мужчин',
			'link5' => 'Фито - экспертиза',
		);
		
        $this->render('main_menu',
            array(
                'menu' => $menu
            ));
    }
}