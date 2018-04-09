<?php

    $title          = $res->seo->get('title', title_case( current_route() ) );
    $description    = $res->seo->get('description', config('seo.description'));
    $picture        = $res->seo->get('picture', config('seo.picture'));
    $keywords       = $res->seo->get('keywords',config('seo.keywords'));
    $author         = $res->seo->get('author',config('seo.name'));

?>

<title>{{ $title }} | {{ config('seo.name', config('app.name')) }}</title>
<meta name="description" content="{{ $description }}" />

<meta name="author" content="{{ $author }}"/>

@if( $keywords )
    <meta name="keywords" content="{{ $keywords }}" />
@endif

<meta property="og:title" content="{{ $title }}" />
<meta property="og:description" content="{{ $description }}" />

@if( $picture )
    <meta property="og:image" content="{{ $picture }}" />
@endif

@yield('head')

{!! $res->seo->metas() !!}