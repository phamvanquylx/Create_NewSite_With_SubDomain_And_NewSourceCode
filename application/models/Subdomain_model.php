<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subdomain_model extends CI_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();
		
	}

	public function insert($data)
	{
		if($data)
		{
			$this->db->insert('tblsubdomain', $data);
		}

		return true;
	}

	public function getAll()
	{
		$this->db->select('*');
		$this->db->order_by('id', 'desc');
		$date_result = $this->db->get('tblsubdomain')->result();

		return $date_result;
	}

	public function delete($subdomain)
	{
		if($subdomain)
		{
			$this->db->where('subdomain_name', $subdomain);
			$this->db->delete('tblsubdomain');
		}

		return true;
	}	

}

/* End of file Subdomain_model.php */
/* Location: ./application/models/Subdomain_model.php */