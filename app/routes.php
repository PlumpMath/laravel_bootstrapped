<?php

/**
 * Add any global assets to this array
 *
 * Assets can be called in view through @foreach $asset['css'], $asset['js'], or any file extension,
 * or by the filename itself, $asset['main.css'], etc.
 *
 */
View::share('asset', ViewData::build([

	'//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js',
	'//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.4.4/underscore-min.js',
	'//cdnjs.cloudflare.com/ajax/libs/backbone.js/1.0.0/backbone-min.js',
	'//cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.9.3/typeahead.min.js',
	'main.css',
	'main.min.js',

]));

Route::get('/', function()
{
	return View::make('layouts.main');
});
