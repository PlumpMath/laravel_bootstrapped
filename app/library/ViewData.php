<?php

class ViewData
{
	public static function asset($file)
	{
		$relative = self::path($file);
		$absolute = public_path().DIRECTORY_SEPARATOR.$relative;

		if (file_exists($absolute)) {
			$ext = pathinfo($file, PATHINFO_EXTENSION);
			$asset = $relative.'?v='.self::version($absolute);
		}

		return $asset;
	}

	public static function build($files)
	{
		$built = [];

		foreach ($files as $file) {
			if (preg_match('/(http(s)?:)?\/\//', $file)) {
				$built[pathinfo($file, PATHINFO_EXTENSION)][] = $built[$file] = $file;
			} else {
				$built[pathinfo($file, PATHINFO_EXTENSION)][] = $built[$file] = self::asset($file);
			}
		}

		return $built;
	}

	public static function path($file)
	{
		return pathinfo($file, PATHINFO_EXTENSION).DIRECTORY_SEPARATOR.$file;
	}

	public static function version($file)
	{
		return filemtime($file);
	}
}