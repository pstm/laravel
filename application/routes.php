<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your application using Laravel's RESTful routing and it
| is perfectly suited for building large applications and simple APIs.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|		Route::get('hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Route::post(array('hello', 'world'), function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Route::put('hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/

foreach (Muli::sitemap_routes() as $key => $value) {

	Route::get(
		__('route.' . $key)->get(Muli::get_lang()), array('as' => $key,
			function() use ($key, $value) {
				return View::make('pages.base', array('id' => $key, 'layout' => Muli::set_layout($value)));
			}
		)
	);

}

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Route::get('/', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	$lang = '';
	$lang_segment = URI::segment(1);
	$cookie_lang = Cookie::get('lang');
	$header_lang = substr(Request::server('http_accept_language'), 0, 2);

	if(!isset($lang_segment)) {

    	if(isset($cookie_lang)){

		    Config::set('application.language',  $cookie_lang);
		    $lang = $cookie_lang;

    	} elseif(in_array($header_lang, Config::get('application.languages'))) {

			Config::set('application.language',  $header_lang);
			Cookie::forever('lang', $header_lang);
			$lang = $header_lang;

    	} else {

    		$lang = Config::get('application.language');

    	}

    	return Redirect::to(URL::base() . '/' . $lang . '/');

    } else {

    	// If the current language segment isn't equal to the cookie lang
    	// reset it with the value of $lang_segment.
    	if($lang_segment != $cookie_lang){
    		Cookie::forever('lang', $lang_segment);
    	}

    }


});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});