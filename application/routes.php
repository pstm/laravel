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

Route::get(
	'/', array('as' => 'home',
		function() {
			return View::make('pages.home');
		}
	)
);
Route::get(
	__('route.section1')->get(Muli::get_lang()), array('as' => 'section1',
		function() {
			// return View::make('pages.section1');
			return View::make('pages.base', array('id' => 'section1', 'layout' => 'master'));
		}
	)
);

Route::get(
	__('route.section1_sublevel1')->get(Muli::get_lang()), array('as' => 'section1_sublevel1',
		function() {
			// return View::make('pages.section1_sublevel1');
			return View::make('pages.base', array('id' => 'section1_sublevel1', 'layout' => 'master'));
		}
	)
);

Route::get(
	__('route.section1_sublevel2')->get(Muli::get_lang()), array('as' => 'section1_sublevel2',
		function() {
			// return View::make('pages.section1_sublevel2');
			return View::make('pages.base', array('id' => 'section1_sublevel2', 'layout' => 'master'));
		}
	)
);

Route::get(
	__('route.section2')->get(Muli::get_lang()), array('as' => 'section2',
		function() {
			// return View::make('pages.section2');
			return View::make('pages.base', array('id' => 'section2', 'layout' => 'master'));
		}
	)
);

Route::get(
	__('route.section3')->get(Muli::get_lang()), array('as' => 'section3',
		function() {
			// return View::make('pages.section3');
			return View::make('pages.base', array('id' => 'section3', 'layout' => 'master'));
		}
	)
);

Route::get(
	__('route.section3_sublevel1')->get(Muli::get_lang()), array('as' => 'section3_sublevel1',
		function() {
			// return View::make('pages.section3_sublevel1');
			return View::make('pages.base', array('id' => 'section3_sublevel1', 'layout' => 'master'));
		}
	)
);

Route::get(
	__('route.section3_sublevel2')->get(Muli::get_lang()), array('as' => 'section3_sublevel2',
		function() {
			// return View::make('pages.section3_sublevel2');
			return View::make('pages.base', array('id' => 'section3_sublevel2', 'layout' => 'master'));
		}
	)
);

Route::get(
	__('route.section3_sublevel3')->get(Muli::get_lang()), array('as' => 'section3_sublevel3',
		function() {
			// return View::make('pages.section3_sublevel3');
			return View::make('pages.base', array('id' => 'section3_sublevel3', 'layout' => 'master'));
		}
	)
);

Route::get(
	__('route.util1')->get(Muli::get_lang()), array('as' => 'util1',
		function() {
			// return View::make('pages.util1');
			return View::make('pages.base', array('id' => 'util1', 'layout' => 'master'));
		}
	)
);

Route::get(
	__('route.util2')->get(Muli::get_lang()), array('as' => 'util2',
		function() {
			// return View::make('pages.util2');
			return View::make('pages.base', array('id' => 'util2', 'layout' => 'master'));
		}
	)
);

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
|		Router::register('GET /', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login');
});