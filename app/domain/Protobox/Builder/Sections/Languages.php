<?php namespace Protobox\Builder\Sections;

use DateTimeZone;

class Languages extends Section {

	private $php_versions = [
		'55' => '5.5',
		'54' => '5.4',
		'53' => '5.3'
	];

	public function languages()
	{
		return [
			'php' => 'PHP',
			'hhvm' => 'PHP - HipHop'
		];
	}

	public function defaults()
	{
		$yaml = $this->builder->files()->get(dirname(__FILE__).'/Data/php.yml');
		$data = $this->parser->parse($yaml);

		return [

			//
			// PHP Settings
			//
			
			'php_install' => 1,
			'php_versions' => $this->php_versions,
			'php_modules' => [
				'cli',
				'intl',
				'mcrypt',
				'curl',
				'gd'
			],
			'php_modules_available' => $data['php_modules'],
			'php_ini' => [
				'display_errors' => 'On',
				'display_startup_errors' => 'On',
				'error_reporting' => '-1',
				'short_open_tag' => 'On',
			],
			'php_ini_available' => $data['ini'],
			'php_timezone' => 'America/Chicago',
			'php_timezone_available' => $this->timezone_available(),

			//
			// PEAR
			//

			'pear_install' => 0,
			'pear_modules' => [],
			'pear_modules_available' => $data['pear_modules'],

			//
			// PECL
			//

			'pecl_install' => 0,
			'pecl_modules' => [],
			'pecl_modules_available' => $data['pecl_modules'],

			//
			// Composer
			//

			'composer_install' => 1,

			//
			// Mailcatcher
			//

			'mailcatcher_install' => 0,

			//
			// PHPMyAdmin
			//

			'phpmyadmin_install' => 0,

			//
			// Xdebug
			//

			'xdebug_install' => 1,
			'xdebug_webgrind' => 1,
			'xdebug_settings' => [
				'xdebug.default_enable' => 1,
				'xdebug.remote_autostart' => 0,
				'xdebug.remote_connect_back' => 1,
				'xdebug.remote_enable' => 1,
				'xdebug.remote_handler' => 'dbgp',
				'xdebug.remote_port' => 9000
			],
			'xdebug_settings_available' => $data['xdebug_settings'],

			//
			// Xhprof
			//

			'xhprof_install' => 0,
			'xhprof_xhgui' => 1,
			'xhprof_location' => '/srv/www/web/xhprof',

			//
			// HHVM
			//

			'hhvm_install' => 0

		];
	}

	public function valid()
	{
		$php = $this->builder->request()->get('php');
		$hhvm = $this->builder->request()->get('hhvm');

		// Make sure only one language is selected
		if (
			isset($php['install']) && (int) $php['install'] == 1 &&
			isset($hhvm['install']) && (int) $hhvm['install'] == 1
		)
		{
			$this->setError('Please choose either PHP or HHVM (not both)');

			return false;
		}

		// Check to see if a valid PHP version was selected
		if (
			isset($php['install']) && (int) $php['install'] == 1 && 
			! in_array($php['version'], array_keys($this->versions))
		)
		{
			$this->setError('Please choose a valid PHP version.');

			return false;
		}

		return true;
	}

	public function rules()
	{
		$rules = [];
		$php = $this->builder->request()->get('php');

		if (isset($php['install']) && (int) $php['install'] != 1)
		{
			$rules += [
				'php.version' => 'required'
			];
		}

		return $rules;
	}

	public function fields()
	{
		return [
			'php.version' => 'PHP Version'
		];
	}

	public function load($output)
	{
		return [
			'php' => [
				'install' => isset($output['php']['install']) ? $output['php']['install'] : 0,
				'version' => isset($output['php']['version']) ? $output['php']['version'] : '',
				'modules' => isset($output['php']['modules']) ? $output['php']['modules'] : [],
				'pear' => [
					'install' => isset($output['php']['pear']['install']) ? (int) $output['php']['pear']['install'] : 0,
					'modules' => isset($output['php']['pear']['modules']) ? $output['php']['pear']['modules'] : [],
				]
			],

			'hhvm' => [
				'install' => isset($output['hhvm']['install']) ? $output['hhvm']['install'] : 0,
			]
		];
	}

	public function output()
	{
		$php = $this->builder->request()->get('php');
		$hhvm = $this->builder->request()->get('hhvm');

		return [
			'php' => [
				'install' => isset($php['install']) ? (int) $php['install'] : 0,
				'version' => $php['version'],
				'modules' => $php['modules'],
				'pear' => [
					'install' => isset($php['pear']['install']) ? (int) $php['pear']['install'] : 0,
					'modules' => isset($php['pear']['modules']) ? $php['pear']['modules'] : [],
				],
				'pecl' => [
					'install' => isset($php['pecl']['install']) ? (int) $php['pecl']['install'] : 0,
					'modules' => isset($php['pecl']['modules']) ? $php['pecl']['modules'] : [],
				],
				'composer' => [
					'install' => isset($php['composer']['install']) ? (int) $php['composer']['install'] : 0,
				],
				'mailcatcher' => [
					'install' => isset($php['mailcatcher']['install']) ? (int) $php['mailcatcher']['install'] : 0,
				],
				'phpmyadmin' => [
					'install' => isset($php['phpmyadmin']['install']) ? (int) $php['phpmyadmin']['install'] : 0,
				],
				'xdebug' => [
					'install' => isset($php['xdebug']['install']) ? (int) $php['xdebug']['install'] : 0,
					'webgrind' => isset($php['xdebug']['webgrind']) ? (int) $php['xdebug']['webgrind'] : 0,
					'settings' => isset($php['xdebug']['settings']) ? $php['xdebug']['settings'] : [],
				],
				'xhprof' => [
					'install' => isset($php['xhprof']['install']) ? (int) $php['xhprof']['install'] : 0,
					'xhgui' => isset($php['xhprof']['xhgui']) ? (int) $php['xhprof']['xhgui'] : 0,
				],
				'ini' => $php['ini'],
				'timezone' => $php['timezone'],
			],

			'hhvm' => [
				'install' => isset($hhvm['install']) ? (int) $hhvm['install'] : 0
			]
		];
	}

	//
	// PHP Timzone
	//

	private function timezone_available()
	{
		$zones = [
			'Africa' => DateTimeZone::AFRICA,
			'America' => DateTimeZone::AMERICA,
			'Antarctica' => DateTimeZone::ANTARCTICA,
			'Aisa' => DateTimeZone::ASIA,
			'Atlantic' => DateTimeZone::ATLANTIC,
			'Europe' => DateTimeZone::EUROPE,
			'Indian' => DateTimeZone::INDIAN,
			'Pacific' => DateTimeZone::PACIFIC
		];

		$tzlist =[];

		foreach ($zones as $name => $mask)
		{
			if ( ! isset($tzlist[$name])) $tzlist[$name] = [];

			$tzlist[$name] = DateTimeZone::listIdentifiers($mask);
		}

		$tzlist['UTC'] = ['UTC'];

		return $tzlist;
	}

}