<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PrepareCssCommand extends PrepareParentCommand {
	public $defaults = [
		'source' => 'app/resources/css',
		'output' => 'public/css/main.css',
		'ext'	 => ['css', 'scss', 'sass'],
	];

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'prepare:css';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Concatenate and minify css.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function shell() {
		return shell_exec('sass --no-cache --update '.$this->options['source'].'main.scss:'.$this->options['output'].' --style compressed');
	}
}