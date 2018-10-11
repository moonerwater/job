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
        $userid = $this->userinfo['id'];

        $job = \Job::find(array('user_id ='.$userid, 'order' => 'id desc'));
        $job = $job->toArray();
        foreach($job as $k => $v){
            $applynum = \Apply::find("job_id = ".$v['id'])->count();
            $job[$k]['applynum'] = $applynum;

            $applyYnum = \Apply::find("job_id = ".$v['id']." and type = 'Y' ")->count();
            $job[$k]['applyYnum'] = $applyYnum;
        }
        $data['job'] = $job;
        //
        $this->view->setVar('data', $data);
        
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
        $user->wxcode_img = $file;
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

    public function releaseAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

        $jobid = $this->request->get('jobid','int');
        if($jobid){
            $jobinfo = \Job::findFirstById($jobid);
            $data['jobinfo'] = $jobinfo->toArray();
        }

        $data['userinfo'] = $this->userinfo;
        $this->view->setVar('data', $data);
    }

    public function addjobAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

        $jobid = $this->request->get('jobid','int');
        $title = $this->request->get('title');
        $province = $this->request->get('province');
        $city = $this->request->get('city');
        $district = $this->request->get('district');
        $address = $this->request->get('address');
        $roomno = $this->request->get('roomno');
        $lng = $this->request->get('lng');
        $lat = $this->request->get('lat');
        $content = $this->request->get('content');
        $type = $this->request->get('type');
        $sex = $this->request->get('sex');
        $num = $this->request->get('num');
        $salary_type = $this->request->get('salary_type');
        $salary = $this->request->get('salary');
        $salary_time = $this->request->get('salary_time');
        $start_date = $this->request->get('start_date');
        $end_date = $this->request->get('end_date');
        $start_time = $this->request->get('start_time');
        $end_time = $this->request->get('end_time');
        $real_name = $this->request->get('real_name');
        $company_name = $this->request->get('company_name');
        $phone = $this->request->get('phone');
        $wxcode_img = $this->request->get('wxcode_img');

        if($jobid){
            $job =\Job::findFirst([' user_id = ?1 and id = ?2 ', 'bind'=>[ 1=>$userid, 2=>$jobid ] ]);
            if (!$job) {
                $this->replyFailure('jobid error 2');
                return '';
            }
        }

        if (!$title || !$province || !$city || !$district || !$address || !$lng || !$lat || !$content || !$type || !$sex
            || !$num || !$salary_type || !$salary || !$salary_time || !$start_date|| !$end_date||
            !$start_time|| !$end_time|| !$real_name|| !$company_name|| !$phone|| !$wxcode_img) {
            if(!$title){
                $this->replyFailure('title error');
            }
            if(!$province){
                $this->replyFailure('province error');
            }
            if(!$city){
                $this->replyFailure('city error');
            }
            if(!$district){
                $this->replyFailure('district error');
            }
            if(!$address){
                $this->replyFailure('address error');
            }
            if(!$lng){
                $this->replyFailure('lng error');
            }
            if(!$lat){
                $this->replyFailure('lat error');
            }
            if(!$content){
                $this->replyFailure('content error');
            }
            if(!$type){
                $this->replyFailure('type error');
            }
            if(!$sex){
                $this->replyFailure('sex error');
            }
            if(!$num){
                $this->replyFailure('num error');
            }
            if(!$salary_type){
                $this->replyFailure('salary_type error');
            }
            if(!$salary){
                $this->replyFailure('salary error');
            }
            if(!$salary_time){
                $this->replyFailure('salary_time error');
            }
            if(!$start_date){
                $this->replyFailure('start_date error');
            }
            if(!$end_date){
                $this->replyFailure('end_date error');
            }
            if(!$start_time){
                $this->replyFailure('start_time error');
            }
            if(!$end_time){
                $this->replyFailure('end_time error');
            }
            if(!$real_name){
                $this->replyFailure('real_name error');
            }
            if(!$company_name){
                $this->replyFailure('company_name error');
            }
            if(!$phone){
                $this->replyFailure('phone error');
            }
            if(!$wxcode_img){
                $this->replyFailure('wxcode_img error');
            }
            //$this->replyFailure('data error');
            return '';
        }

        $file = $this->base64_image_content($wxcode_img,'/upload/wxcode');
        if (!$file) {
            $this->replyFailure('wx code error');
            return '';
        }

        if($jobid){
            $job =\Job::findFirst([' user_id = ?1 and id = ?2 ', 'bind'=>[ 1=>$userid, 2=>$jobid ] ]);
        }
        else{
            $job = new \Job();
        }
        $job->user_id = $userid;
        $job->title = $title;
        $job->province = $province;
        $job->city = $city;
        $job->district = $district;
        $job->address = $address;
        $job->roomno = $roomno;
        $job->lng = $lng;
        $job->lat = $lat;
        $job->content = $content;
        $job->type = $type;
        $job->sex = $sex;
        $job->num = $num;
        $job->salary_type = $salary_type;
        $job->salary = $salary;
        $job->salary_time = $salary_time;
        $job->start_date = $start_date;
        $job->end_date = $end_date;
        $job->start_time = $start_time;
        $job->end_time = $end_time;
        $job->real_name = $real_name;
        $job->company_name = $company_name;
        $job->phone = $phone;
        $job->wxcode_img = $file;
        $job->create_time = time();
        $job->last_time = time();
        $job->save();

        $this->reply('success', 0, $result);

    }

    public function deljobAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

        $jobid = $this->request->get('jobid','int');

        if (!$jobid) {
            $this->replyFailure('jobid error');
            return '';
        }

        $job =\Job::findFirst([' user_id = ?1 and id = ?2 ', 'bind'=>[ 1=>$userid, 2=>$jobid ] ]);
        if (!$job) {
            $this->replyFailure('jobid error 2');
            return '';
        }
        else{
            $job->delete();
        }
        $this->reply('success', 0, $result);
    }

    public function signlistAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

        $jobid = $this->request->get('jobid','int');
        if (!$jobid) {
            $this->replyFailure('jobid error');
            return '';
        }
        $job =\Job::findFirst([' user_id = ?1 and id = ?2 ', 'bind'=>[ 1=>$userid, 2=>$jobid ] ]);
        if (!$job) {
            $this->replyFailure('jobid error 2');
            return '';
        }

        $apply = \Apply::find("job_id = ".$jobid);
        $apply = $apply->toArray();
        foreach($apply as $k => $v){
            $job = \Job::findFirstById($v['job_id']);
            $apply[$k]['title'] = $job->title;
            $user = \User::findFirstById($v['user_id']);
            $apply[$k]['real_name'] = $user->real_name;
            $apply[$k]['headimgurl'] = $user->headimgurl;
            $apply[$k]['phone'] = $user->phone;
        }
        $data['apply'] = $apply;
        //
        $this->view->setVar('data', $data);


    }

    public function changeapplyAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

        $applyid = $this->request->get('applyid','int');
        $type = $this->request->get('type','string');
        if (!$applyid) {
            $this->replyFailure('applyid error');
            return '';
        }
        if ($type != 'Y' && $type != 'N' ) {
            $this->replyFailure('type error');
            return '';
        }
        $apply = \Apply::findFirstById($applyid);
        $job = \Job::findFirstById($apply->job_id);
        if($job->user_id != $userid){
            $this->replyFailure('power error');
            return '';
        }
        $apply->type = $type;
        $apply->save();
        $this->reply('success', 0, $result);
    }

    public function userresumeAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

        $applyid = $this->request->get('applyid','int');
        if (!$applyid) {
            $this->replyFailure('applyid error');
            return '';
        }
        $apply = \Apply::findFirstById($applyid);
        $job = \Job::findFirstById($apply->job_id);
        if($job->user_id != $userid){
            $this->replyFailure('power error');
            return '';
        }
        $user = \User::findFirstById($apply->user_id);
        $data['userinfo'] = $user->toArray();
        //
        $this->view->setVar('data', $data);
    }

    public function settingAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

    }

    public function aboutusAction()
    {
        
    }





    
}
