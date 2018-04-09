- [Installation](#installation)
- [Getting Started](#getting-started)

## Application
  - [Constants](#constans)
  - [Application](#application)

## The Basics
  - [Config](#config)
  - [Views](#views)
  - [Layouts](#layouts)
  - [Router](#router)
  - [Localization](#localization)
  - [Auth](#auth)
  - [Cache](#cache)
  - [Logs](#logs)
  - [Request](#request)
  - [Validations](#validations)
  - [Response](#response)
  - [Session](#session)
  - [Cookie](#cookie)
  - [Translations](#translations)
  - [Actions](#actions)
  - [Helpers](#helpers)

## Plugins
  - [Api](#api)
  - [GraphQL](#graphql)
  - [Database](#database)
  - [Facebook](#facebook)
  - [Mail](#mail)
  - [Alerts](#alerts)
  - [Seo](#seo)


# Installation
In your composer.json file include a direct link to this repository:
```
"repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/travelience/Aida"
    }
  ],
  "require": {
    "travelience/aida": "dev-master"
  }
```

# Getting Started
Create hte folder **/public** and inside a **index.php** file with the following code inside:

```php
<?php

require '../vendor/autoload.php';

$app = Travelience\Aida\Application::getInstance();
$app->run();
```

for a base project visit this repository:
https://github.com/travelience/Aida-base


# Constans
There are few constans you can use inside Aida framework

```
- FRAMEWORK_PATH
- ROOT_PATH
- CONFIG_PATH
- VIEWS_PATH
- ASSETS_PATH
- LOCALES_PATH
- PAGES_FOLDER
```

# Application
If you plan to extend the Framework you can use the following api:

```php

$app->set('api', new Api()); // set variable to context
$app->use( $callback($req, $res) );
$app->use(new Middleware()); // with the method handle($req, $res)

// $event: init, before , after, error
$app->on($event, $callback($req, $res));

// router
// $config['name' => '', 'middlewares' => [], 'page' => ''];
$app->get( $path, $config, $callback($req, $res) );
$app->post( $path, $config, $callback($req, $res) );
$app->any( $path, $config, $callback($req, $res) );

```

# Config
Aida framework have few config files and there are two ways to set their values.

1) create files in your **/config** folder
    - app.php
    - mail.php
    - seo.php
    - services.php
    - database.php
    - routes.php
    - plugins.php
    - actions.php
    - middlewares.php
2) create a **.env** file with the configuration variables

```php
// app.php

return [
    'name' => env('APP_NAME', 'Aida'),
    'domain' => env('APP_DOMAIN'),
    'locales' => ['en', 'es'],
    'locale' => env('LOCALE','en'),
];
```

```php
// database.php

return [
    'driver' => env('DB_DRIVER', 'mysql'), 
    'host' => env('DB_HOST', 'host'),
    'port' => env('DB_PORT', 3306),
    'database' => env('DB_DATABASE'),
    'username' => env('DB_USERNAME'),
    'password' => env('DB_PASSWORD'),
    'charset' => env('DB_CHARSET', 'utf8'),
    'collation' => env('DB_COLLOCATION', 'utf8_unicode_ci'),
    'prefix' => env('DB_PREFIX', '')
];
```

```php
// mail.php

return [
    'host' => env('MAIL_HOST'),
    'port' => env('MAIL_PORT'),
    'username' => env('MAIL_USERNAME'),
    'password' => env('MAIL_PASSWORD'),
    'from' => env('MAIL_FROM_NAME'),
    'from_email' => env('MAIL_FROM'),
];
```

```php
// seo.php

return [

    'name' => 'Aida',
    'description' => 'Aida App description',
    'picture' => false,
    'keywords' => '1,2,3,4',

];
```

```php
// services.php

return [
    // 'api' => 'https://.../api',

    'facebook' => [
        'app_id' => env('FACEBOOK_ID'),
        'app_secret' => env('FACEBOOK_SECRET')
    ],

    // 'graphql' => 'http://.../graphql'
];
```

```php
// routes.php
$app->get('/contact', ['name' => 'contact']);
```

```php
// actions.php
function GET_POSTS( $req, $res, $payload )
{
    return $res->graphql->response('GetPosts', $payload);
}
```

```php
// plugins.php

// Api
$app->withApi('http://domain.com/api/');

// Facebook
$app->withFacebook();

// Api Manually
$api = api('https://domain2.com/api/');
$app->set('api2', $api);
```

```php
// middlewares.php

$app->router->middleware('auth', function($req, $res){
        
    if( is_route('private') && !$res->auth->check() )
    {
        $res->alert('No access', 'danger');
        $res->redirect('home');
    }

});

$app->on('error', function($req, $res){
    echo "HAS ERROR";
});
```

# Views
The template engine that Aida framework use is Blade, for more information please check this documentation: https://laravel.com/docs/5.6/blade

you can set the views from your routes like this:
```php

$app->get('/contact', ['name' => 'contact'] , function($req, $res){
    $res->render('pages.contact');
});

```

# Layouts
The recommended way to create layouts with blade is creating the files inside the folder **/views/layouts/default.blade.php** and put here the content of the layout, example:

```php

<html lang="en">
  <head>
    @include('Aida::head')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  </head>

  <body>
    
    <section>
        @yield('content')
    </section>
    
  </body>

  <script src="{{ mix('js/app.js') }}"></script>

</html>

```

# Router
There are two ways to manage routes in Aida Framework.

### 1) creating the file **config/routes.php**

```php
$app->get(
    $path='/contact',
    $config=['name' => 'contact'],
    $callback($req, $res) // optional
);
```

### 2) using the **dynamic router**.
when you create files inside **views/pages** automatically the routes will match with that files, example for:

- /views/pages/contact.blade.php = `/contact`
- /views/pages/post/_id.blade.php = `/post/:id`

### Route Parameters
To use the route parameters for example for: `/post:/id` you can use **$req->param['id']**

# Localization
To use localization you need to add in **/config/app.php** the locales you want to support, example:

```php

return [
    'locales' => ['en','es']
]
```

and then all the routes will be prepended by the current locale of the user browser by default.

# Auth
To manage authentication you have few methods:

```php

$data = ['first_name' => 'Rodrigo'];

$res->auth->authenticate($data);
$res->auth->logout();
$res->auth->user()->first_name
```

the **authenticate($data)** method store the information of the authenticated user and save in a cookie.

# Cache
The cache in Aida Framework is by file. example:

```php

// cache($key, $callback($req, $res), $duration)

$posts = cache('posts', function($req, $res){
    return $res->api->call('/posts', ['limit' => 5]);
})

```

# Logs
Aida Framework use monolog.
Default error logs are saved by default in the *errors.log* file, if you want to set your own logs, use the **$res->log** param in the response.

example:
```php
$res->log->warning('warning example');
```

# Request
The **$req** methods are:

```php
$req->all();
$req->has($key);
$req->get($key, $default);
$req->onSubmit();
$req->params();
$req->param[$key];
$req->old($key, $default);
$req->getCurrentRoute();
```

# Validation
the **$req** also have access to the validations methods, example:

```php
$req->validate([
    'name' => 'required|min:5',
    'email' => 'required|email'
]);

$req->hasErrors();
$req->hasError($key);
$req->getError($key);
$req->hasError($key, 'error');
```

# Response
the **$res** methods are:
```php
$res->locales();
$res->alert($text, $style='success');
$res->cookie($key, $value, $duration);
$res->redirect($to, $code=302); // $to can be route name
$res->reload();
$res->render($template, $variables=[]);
$res->{$context}; //  graphql, api, etc...  
```

# Session
the session functions are:
```php
session($key);
session($key, $value);
session_forget($key);
session_flush();
```

# Cookie
the cookie helper is:
```php
cookie($key, $value, $duration);
```

# Translations
To use translations in Aida Framework, you have to create the translations files inside: **config/locales/en.php** example:

```php
// en.php

return [
    'home' => 'Home',
    'contact' => 'Contact',
    'hello' => 'Hello :name',
];
```

use example:
```php
__('home'); // HOme
__('hello', ['name' => 'Rodrigo']); // Hello Rodrigo
```

# Actions
To separate most of your code logic outside your template, Aida framework provide you a way to set dispatch and set actions. example:
```php
// config/actions.php

function GET_POSTS( $req, $res, $payload )
{
    return $res->graphql->response('GetPosts', $payload);
}
```

then inside your templates:
```php
// views/pages/index.blade.php

// dispatch($function, $payload)
$posts = dispatch('GET_POSTS', ['limit' => 15]);
```

# Helpers
This is the list of functions helpers:

### General
- app();
- db();
- cookie($key, $value, $duration);
- session($key, $value);
- session_forget($key)
- session_flush()
- array_remove_null($item)
- graphql($host, $headers=[])
- facebook( $config=false )
- api($host)
- redirect($to, $code='302')
- __($key, $params=[], $default=false)
- cache($key, $callback=false, $duration=60)
- array_to_table($data, $class='', $style='')
- array_to_ul( $array, $class='', $style='' )

### Config
- config($path, $default=false)
- env($key, $default=false)
- dispatch( $method, $payload=[] )

### Paths
- mix($path)
- public_path()
- base_path()
- framework_path()
- config_path()
- locales_path()

### Routes
- is_page($page)
- is_page_path($page)
- reload()
- current_domain()
- current_url($params=false)
- is_route( $name )
- is_route_path( $name )
- current_route()
- localization_url($locale, $url=false)
- route($name, $params=[])

# Api
If you want to make calls to a REST api, you have to add in the context the api.

```php

// config/plugins.php

$app->withApi('https://domain.com/api/')

// or manually
$api = api('https://domain.com/api/');
$app->set('api', $api);
```

and then use like this:
```php
$res->api->call('/posts');
```

# GraphQL
To use graphql, you need to add a GraphQL instance in the context.

```php
// config/plugins.php

$headers = [];

if( $app->auth->check() )
{
    $headers['Authorization'] = $app->auth->user()->token;
}

$app->withGraphQL('http://api.demo.com/graphql', $headers);

// or manually
$graphql = graphql('http://api.demo.com/graphql', $headers);
$app->set('graphql', $graphql);
```

to write the queries you have two ways:

1) add a variable with the query string
```php
$query = "query posts($limit: Int!) {
    posts(limit: $limit){
        id,
        title
    }
}";
$posts = $res->graphql->response($query, $variables=['limit' => 5]);
```

2) Create a file in **config/graphql/GetPosts.gql** with the query and then pass like this:
```php
$posts = $res->graphql->response('GetPosts', $variables=['limit' => 5]);
```

# Database
If you need to get information from a database or perform inserts, updates, by default it's disabled, but you can activate like:

```php
$app->withDatabase();
```

example use:
```php
$posts = $res->db->table('posts')->orderBY('id','DESC')->get();

$res->db->table('posts')->insert([
    'title' => 'The Title',
    'content' => 'The Content'
]);
```

# Facebook
Adding a Facebook login in your website is super simple. first you need to set your **FACEBOOK_ID** and **FACEBOOK_SECRET** in your **.env** file, and add the plugin.

```php
// config/plugins.php

$app->withFacebook();
```

example use:
```html
<a href="{{ $res->facebook->login() }}">Facebook Login</a>
```

# Mail
First you need to set your mail configuration in the **.env** file or in the **config/mail.php**. 

```php
$res->mail->subject('The Subject');
$res->mail->to( 'example@domain.com' );
$res->mail->content('The Content'); // content or template
$res->mail->template('mails.test', compact('item') ); // blade template
$sent = $res->mail->send();
```

# Alerts
Aida frameowrk have already a alert system integrated. you can use the default bootstrap template including in your layour:
```html
@include('Aida::alerts')
```

then you can set your alerts any where in your application with:
```php
$res->alert($text, $style='success');
```

# Seo
This framework provide a easy way to manage the SEO of your website.

#### 1) configurate basic rules in **config/seo.php**
```php
return [

    'name' => 'Aida',
    'description' => 'Aida App description',
    'picture' => false,
    'keywords' => '1,2,3,4',

];
```

#### 2) add the head template to your layout
with this you will get the default SEO setup for each page.

```html
<html lang="{{ session('lang') }}">
<head>
@include('Aida::head')
</head>
...
```

#### 3) customize title, description, etc for each page.
```php
$res->seo->set('title', 'Contact Page');
$res->seo->set('description','Contact description');
$res->seo->set('author', 'Aida');
$res->seo->set('keywords', '...');
```

#### 4) to extend the seo params:

1) set own variables
```php
$res->seo->set('app_id', '123456');
```

2) in your layout
```html
<html lang="{{ session('lang') }}">
<head>
    @include('Aida::head')
    <meta name="app_id" content="{{ $res->seo->get('app_id') }}"/>
</head>
...
```

