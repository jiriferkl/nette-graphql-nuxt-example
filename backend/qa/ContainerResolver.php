<?php declare(strict_types = 1);

namespace Qa;

use Nette\DI\Container;
use PHPStan\ShouldNotHappenException;

final class ContainerResolver
{

	private ?Container $container;

	public function __construct(?string $containerLoader)
	{
		if ($containerLoader === null) {
			return;
		}

		$this->container = $this->loadContainer($containerLoader);
	}

	private function loadContainer(string $containerLoader): ?Container
	{
		if (
			!file_exists($containerLoader)
			|| !is_readable($containerLoader)
		) {
			throw new ShouldNotHappenException('Container could not be loaded');
		}

		return require $containerLoader;
	}

	public function getContainer(): Container
	{
		if ($this->container === null) {
			throw new ShouldNotHappenException('Container is not loaded');
		}

		return $this->container;
	}

}
