<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PrepareJsCommand extends PrepareParentCommand {
	public $defaults = [
		'source' => 'app/resources/js',
		'output' => 'public/js/main.min.js',
		'ext'	 => ['js'],
	];

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'prepare:js';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Concatenate and minify javascript.';

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
		return shell_exec('cat '.$this->options['source'].'*.js | uglifyjs -o '.$this->options['output']);
	}
}