<?php declare(strict_types=1);

namespace App;

use Contributte\Bootstrap\ExtraConfigurator;
use Exception;

final class Bootstrap
{

	public static function boot(): ExtraConfigurator
	{
		$configurator = new ExtraConfigurator();
		$configurator->setEnvDebugMode();

		$configurator->enableTracy(__DIR__ . '/../log');
		$configurator->setTempDirectory(__DIR__ . '/../temp');

		if (getenv('NETTE_ENV', true) === 'dev') {
			$configurator->addConfig(__DIR__ . '/../config/env/dev.neon');
		} else if (getenv('NETTE_ENV', true) === 'prod') {
			$configurator->addConfig(__DIR__ . '/../config/env/prod.neon');
		} else {
			throw new Exception('Missing NETTE_ENV env');
		}

		return $configurator;
	}

}
