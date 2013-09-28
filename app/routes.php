<?php

View::share('assets', (new AssetCollection)->dev()->build());

Route::get('/', function()
{
	return View::make('layouts.main');
});

Route::test('/test/{test}', function ($test)
{
	$sass_path = app_path().'/assets/css/tests/'.$test;
	shell_exec('sass --no-cache --update '.$sass_path.'.sass:'.$sass_path.'.css');
	$css = file_get_contents($sass_path.'.css');
	shell_exec('rm '.$sass_path.'.css');

	$data = [
		'style' => $css,
		'content' => View::make('tests.'.$test),
	];

	return View::make('layouts.test', $data);
});
