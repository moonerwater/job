<?php

/**
 * DbController
 *
 * Manage errors
 */
class ManagerController extends ControllerH5
{
    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction() {
        $this->checkNoUserGoLogin();
        
    }

    public function adminAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

        $data['userinfo'] = $this->userinfo;
        $this->view->setVar('data', $data);
    }

    public function signlistAction()
    {
        
    }

    public function releaseAction()
    {
        
    }

    public function modifyAction()
    {
        
    }

    public function userresumeAction()
    {
        
    }

    public function infoAction()
    {
        
    }

    
}
