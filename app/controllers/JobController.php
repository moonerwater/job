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

    public function indexAction() {
        $this->checkNoUserGoLogin();
        $userid = $this->userinfo['id'];

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
            $where .= " and sex = '$sex' ";
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
            $data['jobinfo'] = $jobinfo->toArray();
        }

        $data['userinfo'] = $this->userinfo;
        $this->view->setVar('data', $data);

    }

    public function userAction()
    {
        
    }

    public function resumeAction()
    {
        
    }

    public function myjobAction()
    {
        
    }

    public function signdoneAction()
    {
        
    }

    public function purseAction()
    {
        
    }

    public function bindtelAction()
    {
        
    }

    public function aboutusAction()
    {
        
    }

    public function settingAction()
    {
        
    }
}
