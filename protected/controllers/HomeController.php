<?php

class HomeController extends Controller
{
    public function actionIndex()
	{
		$this->addPageTitlePart('Главная');
    	$this->render('home');
	}
}