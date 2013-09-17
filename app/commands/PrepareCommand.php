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
	protected $description = 'Concatenates and minifies javascript and css.';

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
		$this->info("Polling for changes. Ctrl^C to exit.\n");

		while(1) {
			$this->call('prepare:css', []);
			$this->call('prepare:js',  []);
			sleep(1);
		}
	}
}