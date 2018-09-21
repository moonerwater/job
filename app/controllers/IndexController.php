<?php

class IndexController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {
        //$this->view->disable();
        //echo '金职链还在开发中';
        $this->response->redirect('/job/index');
    }
    public function enAction()
    {
        
    }

    public function hkAction()
    {
        
    }

    public function apiAction()
    {
        
    }
    
	public function animationTestAction()
    {
        
    }

    public function faceAction()
    {
        
    }

}
