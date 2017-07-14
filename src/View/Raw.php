<?php

namespace Tau\View;

use Tau\View\BaseView;

class Raw extends BaseView
{
	protected static $config;

	public function __construct(array $config) {
		self::$config = $config;
	}

	public function render($file, $content = array()) {
		include(self::$config['views'].'/'.$file);
	}
}
