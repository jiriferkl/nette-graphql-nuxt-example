<?php declare(strict_types=1);

namespace App\Model\Command\Generator;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GraphqlAllGenerator extends GraphqlGenerator
{

	protected static $defaultName = 'generate:all';

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$names = [
			GraphqlInputGenerator::getDefaultName(),
			GraphqlQueryRequestGenerator::getDefaultName(),
			GraphqlQueryResolverGenerator::getDefaultName(),
			GraphqlTypeResolverGenerator::getDefaultName(),
		];

		foreach ($names as $name) {
			$this->getApplication()->find($name)->run($input, $output);
		}

		return Command::SUCCESS;
	}

}
