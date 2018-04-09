<?php

namespace Travelience\Aida\Api;

class Api {

  public $base_uri; 
  public $params = [];
  
  public function __construct($base_uri=false, $params=false)
  {
    
    if( $base_uri )
    {
      $this->base_uri = $base_uri;
    }

    if( $params )
    {
      $this->params = $params;
    }
      
  }

  public function get( $method, $params=[])
  {
    return $this->call($method, $params, 'GET');
  }
  
  public function post( $method, $params=[] )
  {
      return $this->call($method, $params, 'POST');
  }

  public function call( $method, $params=[], $type='GET' )
	{
        
    $params = array_merge($params, $this->params);

    $query = http_build_query($params);
    
    $url = $this->base_uri . $method; 

    if( $type == 'GET' )
    {
      $url .= '?' . $query;
    }

		$ch = curl_init( $url );

		if( $type == 'POST' )
		{
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
    }
	      
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$r = @curl_exec($ch);
		curl_close($ch);

		// $r = file_get_contents($url);
		$data = json_decode($r);

		return $data;

  }
  
}