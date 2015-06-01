<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Oj_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('pagination');
		$this->load->helper('url');
		$this->load->model('problem_model','pro');
		$this->load->model('problemsubmit_model','ps');
	}
	//载入主页
	public function index(){
		/*$this->load->model('privilege_model');
		$user_id = $this->session->userdata('user_id');	
		$ip = implode($this->privilege_model->get_ip($user_id));
		p($this->privilege_model->get_ip($user_id));
		p($ip);
		p($this->session->userdata('ip'));
		die;*/
		$total_rows = $this->pro->problem_all_num();
		$config['base_url'] = site_url('oj_index/home/index');   
		$config['total_rows'] = $total_rows;//记录总数，这个没什么好说的了，就是你从数据库取得记录总数   
		$config['per_page'] = 2; //每页条数。额，这个也没什么好说的。。自己设定。默认为10好像。   
		$config['first_link'] = '首页'; // 第一页显示
		$config['last_link'] = '末页'; // 最后一页显示   
		$config['next_link'] = '下一页 >'; // 下一页显示   
		$config['prev_link'] = '< 上一页'; // 上一页显示   
		$config['full_tag_open'] = '';
		$config['full_tag_close'] = '';
		$config['cur_tag_open'] = '<li><a style="color:white;background-color:black">'; // 当前页开始样式   
		$config['cur_tag_close'] = '</a></li>'; 
        		$config['num_links'] = 20;//    当前连接前后显示页码个数。意思就是说你当前页是第5页，那么你可以看到3、4、5、6、7页。   
        		$config['uri_segment'] = 4; 
		$this->pagination->initialize($config);
		$data['links'] = $this->pagination->create_links();
		$data['offset'] = $this->uri->segment(4);
		if($data['offset'] == null) $data['offset']=0;
		$data['category']=$this->pro->problem_list($config['per_page'], $data['offset']);
		if($this->session->userdata('username') && $this->session->userdata('user_id')) {
			$data['username'] = $this->session->userdata('username');
			$data['user_id'] = $this->session->userdata('user_id');
		}else {			
			$data['username'] = false;
			$data['user_id'] = false;
		}
		$this->load->view('oj_index/problem_list.html',$data);
	}
	//显示题目具体内容
	public function problem(){
		$pid=$this->input->get('pid', TRUE);
		if($this->session->userdata('username') && $this->session->userdata('user_id')) {
			$data['username'] = $this->session->userdata('username');
			$data['user_id'] = $this->session->userdata('user_id');
		}else {			
			$data['username'] = false;
			$data['user_id'] = false;
		}
		$data['problem']=$this->pro->get_problem_id($pid);
		$this->load->view('oj_index/problem.html',$data);
	}
	//比赛列表显示
	public function contest_list(){
		if($this->session->userdata('username') && $this->session->userdata('user_id')) {
			$data['username'] = $this->session->userdata('username');
			$data['user_id'] = $this->session->userdata('user_id');
		}else {			
			$data['username'] = false;
			$data['user_id'] = false;
		}
		$this->load->model('oj_con_model','oj_con');
		$data['con_now'] = $this->oj_con->get_now_contest();
		//分页获取已结束的比赛
		$data['num'] =  $this->oj_con->pass_con_num();
		//后台设置后缀为空，否则分页出错
		$this->config->set_item('url_suffix', '');
		//载入分页类
		$this->load->library('pagination');
		$perPage = 3;
		//配置项设置
		$config['base_url'] = site_url('oj_index/home/contest_list/');
		$config['total_rows'] = $data['num']['count(*)'];
		$config['per_page'] = $perPage;
		$config['uri_segment'] = 4;
		$config['first_link'] = '首页';
		$config['prev_link'] = '上一页';
		$config['next_link'] = '下一页';
		$config['last_link'] = '尾页';
		$config['full_tag_open'] = '';
		$config['full_tag_close'] = '';
		$config['cur_tag_open'] = '<li class="active"><a>'; // 当前页开始样式   
		$config['cur_tag_close'] = '</a></li>'; 

		$this->pagination->initialize($config);

		$data['links'] = $this->pagination->create_links();	
		$offset = $this->uri->segment(4);
		if($offset < 1) $offset = 0;
		//$this->db->limit($perPage, $offset);
		$data['con_pass'] = $this->oj_con->con_pass_list($perPage, $offset);
		/*p($data['con_now']);
		p($data['con_pass']);
		die;*/
		//echo $data['links'];die;
	 	$this->load->view('oj_index/contest_list.html',$data);
	}
	//提交状态显示
	public function status(){
		$data['judge_result']=Array("Pending", "Pending Rejudging", "Compiling", "Running & Judging", "Accepted", "Presentation Error", "Wrong Answer", "Time Limit Exceed", "Memory Limit Exceed", "Output Limit Exceed", "Runtime Error", "Compile Error", "Compile OK","Test Running Done");
		$data['judge_color']=Array("btn_status gray","btn_status btn-info","btn_status btn-warning","btn_status btn-warning","btn_status btn-success","btn_status btn-danger","btn_status btn-danger","btn_status btn-warning","btn_status btn-warning","btn_status btn-warning","btn_status btn-warning","btn_status btn-warning","btn_status btn-warning","btn_status btn-info");
		$limit=0;
		if($data['pagination'] = $this->input->get('pagination')) 
			$limit = $data['pagination']*20-20;
		if($this->input->get('previous')) 
			$data['previous'] = $this->input->get('previous');
		$num=20;
		$data['result'] = $this->ps->problem_status($limit, $num);

		$data['pagination'] = $limit/20+2;

		if($this->session->userdata('username') && $this->session->userdata('user_id')) {
			$data['username'] = $this->session->userdata('username');
			$data['user_id'] = $this->session->userdata('user_id');
		}else {			
			$data['username'] = false;
			$data['user_id'] = false;
		}
	 	$this->load->view('oj_index/status.html', $data);
	}
	//提交页面显示
	public function submitpage(){
		$username = $this->session->userdata('username');
		if($username == null){
			self::problem();
			echo "<script type='text/javascript'>window.onload=function(){document.getElementById('signin').click();}</script>";
		}else{
			$data['pid'] = $this->input->get('pid', TRUE);
			$data['username'] = $this->session->userdata('username');
			$data['user_id'] = $this->session->userdata('user_id');
			$this->load->view('oj_index/submitpage.html', $data);
		}
	}

	public function log_out() {
		$this->session->sess_destroy();
		header('Content-Type:text/html;charset=utf-8');
		echo "<script type='text/javascript'> alert('注销成功 ');history.go(-1); </script>";
	}
}