<?php

namespace Travelience\Aida\Mail;

use Travelience\Aida\Blade\Blade;

class Mail 
{
    public $mailer; 
    public $data;
    public $blade;

    public function __construct()
    {
        $path_views = VIEWS_PATH;
        $path_cache = ASSETS_PATH . '/views';

        $this->blade = new Blade( $path_views, $path_cache );
    }

    public function init()
    {
        $config = config('mail');

        if( isset( $config['from'] ) )
        {
            $this->data['from'] = [ $config['from_email'] => $config['from'] ];
        }

        $transport = (new \Swift_SmtpTransport($config['host'], $config['port'], 'tls'));

        if( isset($config['username']) )
        {
            $transport->setUsername($config['username']);
            $transport->setPassword($config['password']);
        }

        $this->mailer = new \Swift_Mailer($transport);
    }

    public function send()
    {
        
        $this->init();

        $message = (new \Swift_Message( $this->data['subject'] ) )
        ->setFrom($this->data['from'])
        ->setTo($this->data['to'])
        ->setBody($this->data['body'])
        ->setContentType("text/html");

        $result = $this->mailer->send($message);

        if( $result )
        {
            return true;
        }

        return false;
    }

    public function from( $email, $name=false )
    {
        $this->data['from'] = [$email => ($name ?? '')];
    }

    public function to( $email, $name=false )
    {
        $this->data['to'] = [$email => ($name ?? '') ];
    }

    public function content( $content=false, $params=false )
    {
        if( is_array($content) )
        {
            $content = array_to_table( $content );
        }

        $this->data['body'] = $content;
    }

    public function template( $template, $params=[] )
    {
        $view = $this->blade->view()->make( $template, $params )->render();
        $this->data['body'] = $view;
    }

    public function subject( $subject )
    {
        $this->data['subject'] = $subject;
    }

}