<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* @author Yin_CW <[email address]>
* @copyright [2015.04.09]
*/
class Register extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('user_model');
	}
	/**
	 * 获取ajax传回数据验证是否存在
	 * @access public
	 * @return  
	 */
	/*public function index() {
		echo "sidhfi";
	}*/

	public function username_check() {
		$username = $this->input->post('username',TRUE);
		$result = $this->user_model->check_username($username);
		//$this->output->enable_profiler(TRUE);
		if($result > 0)
			echo false;
		else 
			echo true;
	}

	//获取ajax传回数据验证是否存在
	public function email_check() {
		$email = $this->input->post('email',TRUE);
		$result = $this->user_model->check_email($email);
		if($result > 0)
			echo false;
		else 
			echo true;
	}

	//注册
	public function reg_act() {
		//$this->output->enable_profiler(TRUE);
		$this->load->helper('form');
		$this->load->library('form_validation');
		 $config = array(
                                    array(
                                            'field' => 'username',
                                            'label' => '用户名',
                                            //'rules' =>'required'
                                            'rules' => 'required | min_length[6] | max_length[32] | alpha_numeric | xss_clean '
                                         ),
                                    array(
                                            'field' => 'password1',
                                            'label' => '密码',
                                            // 'rules' =>'required'
                                            'rules' => 'required  | min_length[6] | max_length[32] | xss_clean'
                                         ),
                                    array(
                                            'field' => 'password2',
                                            'label' => '确认密码',
                                             //'rules' =>'required'
                                            'rules' => 'required  | min_length[6] | max_length[32] | xss_clean'
                                         ),
                                    array(
                                            'field' => 'email',
                                            'label' => '邮箱',
                                            // 'rules' =>'required'
                                            'rules' => 'required | valid_email | xss_clean '
                                         )
                                    );
		$this->form_validation->set_rules($config);
        		$status = $this->form_validation->run();
        		if($status == false ) {
        			echo "hsdifhisd";
        			die;
        		}else {
        			//$this->load->library('encrypt');
        			$this->load->helper('date');
        			$format = 'DATE_W3C';
			$time = standard_date($format, time());
        			$username = $this->input->post('username',TRUE);
        			$password1 = $this->input->post('password1',TRUE);
        			$password2 = $this->input->post('password2',TRUE);
        			$email = $this->input->post('email',TRUE); 
        			$ip = $this->input->ip_address();
        			if ($this->user_model->check_username($username) > 0 || $password1 != $password2 || $this->user_model->check_email($email) > 0 )
        				echo  2;
        			else {
        				$user_id = md5($username.mt_rand(100,999));
        				$data = array(
        						'user_id' => $user_id,
        						'username' => $username,
        						'password' => md5($password1.substr($username,0,2)),
        						'accesstime' => $time,
        						'reg_time' => $time,
        						'ip' =>  $ip,
        						'email' => $email
        					);
        				$result = $this->user_model->reg_act($data);
        				if($result) 
        					echo true;
        				else
        					echo  4;
        			}

        		}
        		
	}
}