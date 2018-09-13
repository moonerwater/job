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

    public function logoutAction() {
        $this->session->remove('userinfo');
        $this->response->redirect('/manager/index');
    }

    public function adminAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

        $data['userinfo'] = $this->userinfo;
        $this->view->setVar('data', $data);
    }

    public function checkphoneAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

        if(!$this->userinfo['phone']) {
            $this->replyFailure('no phone');
        }
        else{
            $this->reply(true, 0, array());
        }
    }

    public function getphonecodeAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

        $this->view->disable();
        $mobile = $this->request->get('mobile', 'string');
        $tempSms = $this->cache->get('sms_'.date("Ymd").'_'.$mobile);
        $tempSms = $tempSms ? $tempSms : array();
        $tempSmsLast = $tempSms[count($tempSms)-1];
        if (!$this->isPhone($mobile)) {
            $this->replyFailure('手机号码格式不正确');
            return '';
        }
        elseif (count($tempSms) >= 3) {
            $this->replyFailure('相同手机号码一天内只能发送三次短信');
            return '';
        }
        elseif ($tempSmsLast) {
            if (time() - $tempSmsLast['time'] < 60) {
                $this->replyFailure('60秒后才能发送');
                return '';
            }
        }
        $code = rand(1000,9999);
        $sms = new \Sms();
        if (!$sms->sendSms($mobile, 'userSign', array('code' => $code))) {
            $this->replyFailure('发送失败');
            return '';
        }
        $tempSms[] = array('code' => $code, 'time' => time());
        $this->cache->save('sms_'.date("Ymd").'_'.$mobile, $tempSms);

        $this->reply(true, 0, $result);
    }

    public function edituserinfoAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

        $mobile = $this->request->get('mobile', 'string');
        $vcode = $this->request->get('vcode', 'alphanum');
        $real_name = $this->request->get('real_name', 'string');
        $company_name = $this->request->get('company_name', 'string');
        $base64 = $this->request->get('base64');
        $tempSms = $this->cache->get('sms_'.date("Ymd").'_'.$mobile);
        $tempSms = $tempSms ? $tempSms : array();
        $tempSmsLast = $tempSms[count($tempSms)-1];
        if (!$this->isPhone($mobile)) {
            $this->replyFailure('手机号码格式不正确');
            return '';
        }

        if (!($tempSmsLast['code'] && $tempSmsLast['code'] == $vcode)) {
            $this->replyFailure('验证码错误');
            return '';
        }
        if (!$real_name) {
            $this->replyFailure('real_name none');
            return '';
        }
        if (!$company_name) {
            $this->replyFailure('company_name none');
            return '';
        }
        if (!$base64) {
            $this->replyFailure('base64 none');
            return '';
        }

        $file = $this->base64_image_content($base64,'/upload/wxcode');

        $user = \User::findFirstById($userid);
        $user->phone = $mobile;
        $user->real_name = $real_name;
        $user->company_name = $company_name;
        $user->xcode_img = $file;
        $user->last_time = time();
        $user->save();
        //

        $this->reply('success', 0, $result2);
    }

    public function infoAction() {
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

    public function settingAction()
    {
        
    }



    
}
