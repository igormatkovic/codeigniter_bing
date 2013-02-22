<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
	 * Bing.php 
	 * 
	 * Simple CodeIgniter Bing Search Lib
	 * 
	 * @file		bing.php
	 * @path		/CodeIgniter_Bing/library/Bing.php
	 * @license		As you wish
	 * @author		Igor Matkovic
	 * @link		http://www.cod3.me
	 * @email		igor@cod3.me
	 * @version		1.0
	 * @date		22/02/2013/
	 * 
	 * Copyright (c) 2013
	*/


class Bing
{
   
    public function __construct()
    {
    	$CI =& get_instance();
    	$CI->load->config('bing');
        
        $this->_url 		= 'https://api.datamarket.azure.com/Bing/Search';  
        $this->_service_op 	= 'Web'; 
    
        $this->_key 		= $CI->config->item('bing_key');  
         
        
    }
    
    
    /**
     * get_search function.
     * Get the basic search
     * @access public
     * @param mixed $query
     * @return void
     */
    public function get_search($query) {
    
       $this->_service_op = 'Web';
       
	   $response = self::get_query($query);
	   
	   $return = array();
	   
	   
	   //I prefer lower case letters.. so... 
	   foreach($response->d->results as $row) {
		   $result = array();
		   $result['title'] = $row->Title;
		   $result['description'] = $row->Description;
		   $result['url'] = $row->Url;
		   $result['display_url'] = $row->DisplayUrl;
		   
		   $return[] = $result;
	   }
	    
	    return $return;
	    
    }
    
    /**
     * get_images function.
     * Get the images
     * @access public
     * @param mixed $query
     * @return void
     */
    public function get_images($query) {
    
       $this->_service_op = 'Image';
       
	   $response = self::get_query($query);
	   
	   $return = array();
	   
	   
	   //I prefer lower case letters.. so... 
	   foreach($response->d->results as $row) {
		   $result = array();
		   $result['title'] 				= $row->Title;
		   $result['image'] 				= $row->MediaUrl;
		   $result['image_source'] 			= $row->SourceUrl;
		   $result['thumbnail'] 			= $row->Thumbnail->MediaUrl;
		   $result['attributes']['width'] 	= $row->Width;
		   $result['attributes']['height'] 	= $row->Height;
		   $result['attributes']['size'] 	= $row->FileSize;
		   $result['attributes']['type'] 	= $row->ContentType;
		   
		   
		   $return[] = $result;
	   }
	    
	    return $return;
	    
    }
    
    
    
    /**
     * get_videos function.
     * Get Videos
     * @access public
     * @param mixed $query
     * @return void
     */
    public function get_videos($query) {
	    
	    $this->_service_op = 'Video';
   	    $response = self::get_query($query);
		   
   	    $return = array();
		   
		   
	   //I prefer lower case letters.. so... 
       foreach($response->d->results as $row) {
    	   $result = array();
    	   $result['title'] 				= $row->Title;
    	   $result['video'] 				= $row->MediaUrl;
    	   $result['time'] 					= $row->RunTime;
    	   $result['thumbnail'] 			= $row->Thumbnail->MediaUrl;
    	   
    	   $return[] = $result;
       }
	    
	    return $return;
	    
	    
    }
    
    /**
     * get_videos function.
     * Get Videos
     * @access public
     * @param mixed $query
     * @return void
     */
    public function get_news($query) {
	    
	    $this->_service_op = 'News';
   	    $response = self::get_query($query);
		   
   	    $return = array();
		   
		   
	   //I prefer lower case letters.. so... 
       foreach($response->d->results as $row) {
    	   $result = array();
    	   $result['title'] 				= $row->Title;
    	   $result['url'] 					= $row->Url;
    	   $result['date'] 					= $row->Date;
    	   $result['description'] 			= $row->Description;
    	   $result['source_title'] 			= $row->Source;
    	   
    	   $return[] = $result;
       }
	    
	    return $return;
	    
	    
    }
    
    
    /**
     * get_query function.
     * Simple construct and get query results
     * @access private
     * @param string $query
     * @return void
     */
    private function get_query($query) {
    
    	// Encode the query and the single quotes that must surround it.
    	$query = urlencode("'{$query}'");
    

    	// Construct the full URI for the query.
    	$requestUri = $this->_url."/".$this->_service_op."?\$format=json&Query=".$query; 
    
    
	    $auth = base64_encode("$this->_key:$this->_key");
	    
		$data = array(
			'http' => array(
			'request_fulluri' => true,
			'ignore_errors' => true,  			// ignore_errors can help debug – remove for production. This option added in PHP 5.2.10
			'header' => "Authorization: Basic $auth")
		);

	    // Encode the credentials and create the stream context.
	    $context = stream_context_create($data);
		
		// Get the response from Bing.
		$response = file_get_contents($requestUri, 0, $context); 
		
		
		return json_decode($response);
		


    }
    
    
    public function set_service($name) {
	    $this->_service_op = $name;
    }
}