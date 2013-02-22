<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Example extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		
	}
	
	function index() {
	
		$keyword = 'Iphone 5';
		
		
		//Lets do a bing search for this thing
		$this->load->library('bing');
		
		
		
		$stuff = array();
		
		
		$stuff['search'] 	= $this->bing->get_search($keyword);
		
		$stuff['images'] 	= $this->bing->get_images($keyword);
		
		$stuff['videos'] 	= $this->bing->get_videos($keyword);
		
		$stuff['news'] 		= $this->bing->get_news($keyword);
		
		
		echo "<pre/>";
		print_r($stuff);
		echo "</pre>";
		
		
	}
	
	
	
}