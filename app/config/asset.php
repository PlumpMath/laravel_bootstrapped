<?php

return [
	'production' => [
		[
			'to' => 'public/style/style.min.css',
			'from' => [
				'app/assets/css/master.sass'
			]
		],
		[
			'to' => 'public/app/autoload.min.js',
			'from' => [
				'app/assets/js/autoload.js'
			]
		],
	],
	'development' => [
	],
];
