<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* @author Yin_CW <[email address]>
* @copyright [2015.07.05]
*/
class Acmer_model extends CI_Model {
	function __construct(){
		parent::__construct();
		require('db_info.inc.php');
	}

	public function user_search($username) {
		$query = "SELECT user_id, solved, reg_time,username FROM users WHERE username = '$username' ";
		$result = mysql_query($query);
		if($result) {
			$num = mysql_num_rows($result);
			if($num > 0) return mysql_fetch_assoc($result);
			else return false;
		} else return false;
	}

	public function user_check($user_id) {
		$query = "SELECT * FROM acmer WHERE id = '$user_id' ";
		$result = mysql_query($query);
		if($result) {
			$num = mysql_num_rows($result);
			if($num == 0) return true;
			else return false;
		} else return false;
	}

	public function acmer_add($data) {
		$query = "INSERT INTO acmer(id,name,poj_name,hdoj_name,cf_name) VALUES('$data[user_id]','$data[user_name]','$data[poj_name]','$data[hdoj_name]','$data[cf_name]') ";
		$result = mysql_query($query);
		return $result;
	}

	public function get_all_acmer() {
		$query = "SELECT * FROM acmer where 1 order by sum10 desc";
		$result = mysql_query($query);
		$data = array();
		while ($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
			$data[] = $row;
		}
		return $data;
	}

	public function acmer_del($name) {
		$query = "DELETE FROM acmer WHERE name = '$name' ";
		$result = mysql_query($query);
		return $result;
	}

}
?>