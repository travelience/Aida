<?php

namespace Travelience\Aida\Facebook;

class Facebook
{

  public $sdk;

  public function __construct( $config=false )
  {
    $config = config('services.facebook', $config);
    
    if( !$config )
    {
      return false;
    }
    
    $this->sdk = new \Facebook\Facebook([
      'app_id' => $config['app_id'],
      'app_secret' => $config['app_secret'],
      'default_graph_version' => 'v2.2',
      'http_client_handler' => 'stream'
    ]);

  }

  public function login( $permissions = ['email'] )
  {
      $helper = $this->sdk->getRedirectLoginHelper();
      $loginUrl = $helper->getLoginUrl( current_url(), $permissions);

      return $loginUrl;
  }

  public function callback()
  {
    
    $helper = $this->sdk->getRedirectLoginHelper();
    
    try {

      $token = $helper->getAccessToken();

      if( !$token )
      {
        return false;
      }

      $response = $this->sdk->get('/me?fields=email,first_name,last_name', $token);
      $user = $response->getGraphUser();

      $data = [
        'id' => $user['id'],
        'token' => $token->getValue(),
        'first_name' => $user['first_name'],
        'last_name' => $user['last_name'],
        'email' => $user['email']
      ];

      return $data;

    } catch(Facebook\Exceptions\FacebookResponseException $e) {
      return false;
    }

    return false;

  }

}
