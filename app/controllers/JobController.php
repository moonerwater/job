<?php

/**
 * DbController
 *
 * Manage errors
 */
class JobController extends ControllerH5
{
    public function initialize()
    {
        parent::initialize();
    }

    public function logoutAction() {
        $this->session->remove('userinfo');
        $this->response->redirect('/job/index');
    }

    public function indexAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];
        $jssdk = new \Jssdk('wx4881a7dbcae7aab1', '39fe7c54ada0e213c5f18b630fb39451');
        $signPackage = $jssdk->getSignPackage();
        $this->view->setVar('sign', $signPackage);

    }

    public function getjobAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

        $where = " end_date >= '".date('Y-m-d')."' ";
        $keyword  = $this->request->get('keyword');
        $city  = $this->request->get('city');
        $district = $this->request->get('district');
        $type = $this->request->get('type');
        $sex = $this->request->get('sex');
        $salary_time = $this->request->get('salary_time');
        $order = $this->request->get('order');
        $orderby = $this->request->get('orderby');

        $lng = $this->request->get('lng');
        $lat = $this->request->get('lat');

        if($keyword){
            $where .= " and title like '%$keyword%' ";
        }
        if($city){
            $where .= " and city = '$city' ";
        }
        if($district){
            $where .= " and district = '$district' ";
        }
        if($type){
            $where .= " and type = '$type' ";
        }
        if($sex){
            $where .= " and (sex = '$sex'  or sex = '不限') ";
        }
        if($salary_time){
            $where .= " and salary_time = '$salary_time' ";
        }

        $listuser = $this->db->fetchAll("SELECT id,title,city,district,start_date,end_date,salary_type,salary,type,
                                        ROUND(6378.138*2*ASIN(SQRT(POW(SIN(($lat*PI()/180-lat*PI()/180)/2),2)+COS($lat*PI()/180)*COS(lat*PI()/180)*POW(SIN(($lng*PI()/180-lng*PI()/180)/2),2)))*1000) AS juli
                                        FROM job WHERE $where order by $order $orderby");
        foreach($listuser as $k => $v){
            $listuser[$k]['days'] = round((strtotime($v['end_date'])-strtotime($v['start_date']))/3600/24)+1;
            if($v['juli'] > 1000){
                $listuser[$k]['juli'] = number_format($v['juli']/1000, 2).'公里';
            }
            else{
                $listuser[$k]['juli'] = $listuser[$k]['juli'].'米';
            }
        }
        $this->reply(true, 0, $listuser);
    }

    public function detailAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

        $jobid = $this->request->get('jobid','int');
        if($jobid){
            $jobinfo = \Job::findFirstById($jobid);
            $jobinfo->view_num = $jobinfo->view_num +1;
            $jobinfo->save();
            $data['jobinfo'] = $jobinfo->toArray();
            $data['jobinfo']['content2'] = nl2br($data['jobinfo']['content']);
            $data['jobinfo']['content3'] = preg_replace("/\s/","",$data['jobinfo']['content']);
            $userinfo = \User::findFirstById($jobinfo->user_id);
            $data['jobuserinfo'] = $userinfo->toArray();
        }

        $data['userinfo'] = $this->userinfo;
        $jssdk = new \Jssdk('wx4881a7dbcae7aab1', '39fe7c54ada0e213c5f18b630fb39451');
        $signPackage = $jssdk->getSignPackage();
        $this->view->setVar('sign', $signPackage);
        $this->view->setVar('data', $data);

    }

    public function checkdataAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

        if(!$this->userinfo['phone']) {
            $this->replyFailure('no phone');
        }
        elseif(!$this->userinfo['real_name'] || !$this->userinfo['identity'] || !$this->userinfo['education']) {
            $this->replyFailure('no other');
        }
        else{
            $this->reply(true, 0, array());
        }
    }

    public function applyAction(){
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

        $jobid = $this->request->get('jobid','int');
        $job = \Job::findFirstById($jobid);
        if (!$job) {
            $this->replyFailure('no this job');
            return '';
        }

        $apply =\Apply::findFirst([' user_id = ?1 and job_id = ?2 ', 'bind'=>[ 1=>$userid, 2=>$jobid ] ]);
        if ($apply) {
            $this->replyFailure('apply is exist');
            return '';
        }

        $apply = new \Apply();
        $apply->user_id = $userid;
        $apply->job_id = $jobid;
        $apply->create_time = time();
        $apply->last_time = time();
        $apply->save();
        $this->reply(true, 0, array('apply_id'=>$apply->id));
    }

    public function signdoneAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

        $applyid = $this->request->get('applyid','int');
        if (!$applyid) {
            $this->replyFailure('applyid error');
            return '';
        }
        $apply = \Apply::findFirstById($applyid);
        if($apply->user_id != $userid){
            $this->replyFailure('power error');
            return '';
        }
        $jobinfo = \Job::findFirstById($apply->job_id);
        $data['jobinfo'] = $jobinfo->toArray();
        $this->view->setVar('data', $data);
    }

    public function userAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

        $data['userinfo'] = $this->userinfo;
        $this->view->setVar('data', $data);
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

        $user = \User::findFirstById($userid);
        $user->phone = $mobile;
        $user->last_time = time();
        $user->save();
        //

        $this->reply('success', 0, $result2);
    }

    public function bindtelAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

        $data['userinfo'] = $this->userinfo;
        $this->view->setVar('data', $data);
    }


    public function resumeAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

        $user = \User::findFirstById($userid);
        $data['userinfo'] = $user->toArray();
        //
        $this->view->setVar('data', $data);
    }

    public function editresumeAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

        $real_name = $this->request->get('real_name');
        $sex = $this->request->get('sex');
        $birth = $this->request->get('birth');
        $identity = $this->request->get('identity');
        $education = $this->request->get('education');
        $experience = $this->request->get('experience');
        $workarea = $this->request->get('workarea');

        if (!$real_name || !$sex || !$birth || !$identity || !$education || !$experience || !$workarea ) {
            $this->replyFailure('data error');
            return '';
        }
        $user = \User::findFirstById($userid);
        $user->real_name = $real_name;
        $user->sex = $sex;
        $user->birth = $birth;
        $user->identity = $identity;
        $user->education = $education;
        $user->experience = $experience;
        $user->workarea = $workarea;
        $user->last_time = time();
        $user->save();
        //

        $this->reply('success', 0, $result2);
    }

    public function myjobAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

        $apply = \Apply::find(array("user_id = ".$userid, 'order' => 'id desc'));
        $apply = $apply->toArray();
        foreach($apply as $k => $v){
            $job = \Job::findFirstById($v['job_id']);
            if($job){
                $apply[$k]['joinfo'] = $job->toArray();
            }
            $user = \User::findFirstById($v['user_id']);
            $apply[$k]['real_name'] = $user->real_name;
            $apply[$k]['headimgurl'] = $user->headimgurl;
            $apply[$k]['phone'] = $user->phone;
        }
        $data['apply'] = $apply;
        $data['userinfo'] = $this->userinfo;
        //
        $this->view->setVar('data', $data);
    }

    public function delapplyAction(){
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

        $applyid = $this->request->get('applyid','int');
        if (!$applyid) {
            $this->replyFailure('applyid error');
            return '';
        }
        $apply = \Apply::findFirstById($applyid);
        if($apply->user_id != $userid){
            $this->replyFailure('power error');
            return '';
        }
        $apply->delete();
        $this->reply('success', 0, $result);
    }

    public function aboutusAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];
    }

    public function settingAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];
    }

    public function purseAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];
    }
}
