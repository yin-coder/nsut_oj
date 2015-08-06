<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contest extends Sch_Controller{
    function __construct() {
        parent::__construct(); 
        $this->load->model('school_contest_model','sch_model');
        $this->load->model('oj_con_model','oj_con');
        $this->load->model('contest_model');
    }
    
    //载入校赛题目页
        public function school_pro_list(){
            if(($a=$this->session->userdata('school_contest')) && ($b=$this->session->userdata('username')) && ($c=$this->session->userdata('user_id')) ){
                                                    $data['contest_id'] =$a;
                                                    $data['username'] =  $b;                                              
			$data['user_id'] = $c;
            }else{
                                                    $data['username'] = false;
			$data['user_id'] = false;
            }
            if(isset($data['contest_id'] )){
                          $data['contest'] = $this->oj_con->con_byId($data['contest_id']);
                          //$data['pro'] = $this->sch_model->contest_pro($data['contest_id']);                  
                          $con_pro_id = $this->contest_model->get_con_pro_id($data['contest_id']);
                          $con_pro_sub = $this->oj_con->get_con_pro_sub($data['user_id'], $data['contest_id']);
                          $con_pro_ac = $this->oj_con->get_con_pro_ac($data['user_id'], $data['contest_id']);
                         $data['pro'] = array();
		foreach ($con_pro_id as $v) {
			$data['pro'][$v['num']] = $v;
			foreach ($con_pro_sub as $sub) {
				if($v['problem_id'] == $sub['problem_id']) {
					$temp = 0;
					foreach ($con_pro_ac as $ac) {
						if($v['problem_id'] == $ac['problem_id']) {
							$data['pro'][$v['num']]['status'] = true;
							$temp = 1;
							break;
						} 
					}
					if($temp == 0) {
						$data['pro'][$v['num']]['status'] = false;
					}
				}
			}
		}
		$data['arr'] = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
                          //p($con_pro_id);p($con_pro_sub);p($con_pro_ac);die;
                          $this->load->view('contest/sch_pro_list.html',$data);
            }
        }
        //提交状态显示
        public function status(){
             if(($a=$this->session->userdata('school_contest')) && ($b=$this->session->userdata('username')) && ($c=$this->session->userdata('user_id')) ){
                                                    $data['contest_id'] =$a;
                                                    $data['username'] =  $b;                                              
			$data['user_id'] = $c;
            }else{
                                                    $data['username'] = false;
			$data['user_id'] = false;
            }
            if(isset($data['contest_id'] )){
                                   $data['contest'] = $this->oj_con->con_byId($data['contest_id']);
		$limit=0;
		if($data['pagination'] = $this->input->get('pagination')) 
			$limit = $data['pagination']*20-20;
		if($this->input->get('previous')) 
			$data['previous'] = $this->input->get('previous');
		$num=20;
		$sum = $this->oj_con->con_problem_status_sum($data['contest_id']);
		//p($sum['count(*)']);
		if($data['pagination'] != 0 && $data['pagination']*20 < $sum['count(*)']) {
			$data['pag'] = true;
		}else if($data['pagination'] == 0 && $sum['count(*)'] > 20) {
			$data['pag'] = true;
		}
		$data['pagination'] = $limit/20+2;
		$data['judge_result']=Array("Pending", "Pending Rejudging", "Compiling", "Running & Judging", "Accepted", "Presentation Error", "Wrong Answer", "Time Limit Exceed", "Memory Limit Exceed", "Output Limit Exceed", "Runtime Error", "Compile Error", "Compile OK","Test Running Done");
		$data['judge_color']=Array("btn_status gray","btn_status btn-info","btn_status btn-warning","btn_status btn-warning","btn_status btn-success","btn_status btn-danger","btn_status btn-danger","btn_status btn-warning","btn_status btn-warning","btn_status btn-warning","btn_status btn-warning","btn_status btn-warning","btn_status btn-warning","btn_status btn-info");
		$data['result'] = $this->oj_con->con_problem_status($data['contest_id'],$limit, $num);
		//p($data);
		$count = count($data['result']);
		$data['arr'] = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		for ($i = 0; $i < $count; $i++) {
			$result = $this->oj_con->get_username($data['result'][$i]['user_id']);
			$data['result'][$i]['username'] = $result['username'];
		}
                        $this->load->view('contest/sch_con_status.html',$data);
            }
        }
        //校赛现场排名
        public function rank(){
            $this->load->view('contest/sch_con_rank.html',$data);
        }
}