<?php declare(strict_types=1);

namespace App\Model\Command\Elastica;

use App\Model\Elastica\ElasticaIndex;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ElasticaCreateIndexCommand extends Command
{

	protected static $defaultName = 'elastica:create-index';

	public function __construct(private readonly ElasticaIndex $index)
	{
		parent::__construct();
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$this->index->createIndex();

		return Command::SUCCESS;
	}

}
