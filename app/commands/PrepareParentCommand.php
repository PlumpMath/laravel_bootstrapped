<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PrepareParentCommand extends Command {
	public $options = [];

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function formatOptions($options) {
		$source = ($this->option('source')) ? $this->option('source') : $this->defaults['source'];
		$output	= ($this->option('output')) ? $this->option('output') : $this->defaults['output'];
		$extensions	= ($this->option('ext')) ? $this->option('ext') : $this->defaults['ext'];

		$source = base_path().DIRECTORY_SEPARATOR.$source.DIRECTORY_SEPARATOR;
		$output = base_path().DIRECTORY_SEPARATOR.$output;
		$pattern = $source.'*{.'.implode(',.', $extensions).'}';

		return [
			'source' 	=> $source,
			'output' 	=> $output,
			'pattern' 	=> $pattern,
		];
	}

	public function getCurrentBuild() {
		$files = glob($this->options['pattern'], GLOB_BRACE);
		$current = false;

		foreach ($files as $file) {
			$time = filemtime($file);

			if ($time > $current) {
				$current = $time;
			}
		}

		return $current;
	}

	public function getLastBuild() {
		$latest_file = $this->options['source'].'.latest';

		if (file_exists($latest_file)) {
			return +file_get_contents($latest_file);
		}

		return false;
	}

	public function updateLast($data) {
		$latest_file = $this->options['source'].'.latest';

		file_put_contents($latest_file, $data);
	}

	public function shell() {
		return shell_exec('');
	}

	public function needsRebuild($current, $last) {
		return $current > $last || $current === false || $last === false;
	}

	public function echoOverwrite() {
		$this->info('['.date('D M j H:i:s Y').'] overwrite '.$this->options['output']);
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$this->options = $this->formatOptions($this->option());

		$current = $this->getCurrentBuild();

		if ($this->needsRebuild($current, $this->getLastBuild())) {
			$this->echoOverwrite();
			$this->shell();
			$this->updateLast($current);
		}
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('source', 's', InputOption::VALUE_REQUIRED, 'Specify source directory.', $this->defaults['source']),
			array('output', 'o', InputOption::VALUE_REQUIRED, 'Specify output file.', $this->defaults['output']),
			array('ext', null, InputOption::VALUE_REQUIRED+InputOption::VALUE_IS_ARRAY, 'Specify source file extensions.', $this->defaults['ext']),
		);
	}

}