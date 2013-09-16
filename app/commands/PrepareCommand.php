<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PrepareCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'prepare';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Concatenates and minifies resources.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$css 	= 'main.css';
		$js  	= 'main.min.js';
		$scss 	= 'main.scss';

		$css_files 	= scandir(app_path().'/resources/css');
		$js_files  	= scandir(app_path().'/resources/js');
		$current_build = false;

		foreach ($css_files as $file) {
			if (pathinfo($file, PATHINFO_EXTENSION) === 'scss' ||
					pathinfo($file, PATHINFO_EXTENSION) === 'css' ||
					pathinfo($file, PATHINFO_EXTENSION) === 'sass') {
				$time = filemtime(app_path().'/resources/css/'.$file);
				if ($time > $current_build) {
					$current_build = $time;
				}
			}
		}

		foreach ($js_files as $file) {
			if (pathinfo($file, PATHINFO_EXTENSION) === 'js') {
				$time = filemtime(app_path().'/resources/js/'.$file);
				if ($time > $current_build) {
					$current_build = $time;
				}
			}
		}

		if (file_exists(app_path().'/resources/.latest')) {
			$last_build = +file_get_contents(app_path().'/resources/.latest');
		} else {
			$last_build = false;
		}

		if ($last_build != $current_build || $last_build = false || $current_build = false) {
			shell_exec('cat app/resources/js/*.js | uglifyjs -o public/js/'.$js);
			shell_exec('sass --no-cache --update app/resources/css/'.$scss.':public/css/'.$css.' --style compressed');
			file_put_contents(app_path().'/resources/.latest', $current_build);
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(

		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(

		);
	}

}