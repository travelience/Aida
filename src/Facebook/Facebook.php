<?php

namespace Travelience\Aida\Facebook;

class Facebook
{

  public $sdk;
  public $config;

  public function __construct( $config=false )
  {
    $this->config = config('services.facebook', $config);
    
    if( !$this->config )
    {
      return false;
    }
    
    $this->sdk = new \Facebook\Facebook([
      'app_id' => $this->config['app_id'],
      'app_secret' => $this->config['app_secret'],
      'default_graph_version' => 'v2.2',
      'http_client_handler' => 'stream'
    ]);

  }

  public function login( $permissions = ['email'], $redirect=false )
  {
      $helper = $this->sdk->getRedirectLoginHelper();
      $redirect = ( $redirect ?? $this->config['redirect'] );
      $redirect = ( $redirect ?? current_domain() );
      $loginUrl = $helper->getLoginUrl( $redirect, $permissions);

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
