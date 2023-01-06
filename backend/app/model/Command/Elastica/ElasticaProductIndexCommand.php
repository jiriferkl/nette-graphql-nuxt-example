<?php declare(strict_types=1);

namespace App\Model\Command\Elastica;

use App\Model\Elastica\ProductIndexer;
use App\ModelGenerated\Enum\Language;
use Elastica\Response;
use Exception;
use Nette\Utils\Validators;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

final class ElasticaProductIndexCommand extends Command
{

	protected static $defaultName = 'elastica:index-products';

	public function __construct(private readonly ProductIndexer $productIndexer)
	{
		parent::__construct();
	}

	protected function configure(): void
	{
		$this->addOption(
			name: 'all',
			shortcut: 'a',
			description: 'Index all products in all locales unless language is specified'
		);

		$this->addOption(
			name: 'id',
			shortcut: 'i',
			mode: InputOption::VALUE_REQUIRED,
			description: 'Index only product with this id'
		);

		$this->addOption(
			name: 'lang',
			shortcut: 'l',
			mode: InputOption::VALUE_REQUIRED,
			description: 'Index only index with this locale (if omitted all locales are indexed)'
		);
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$isAll = (bool) $input->getOption('all');
		$id = Validators::isNumericInt($input->getOption('id')) ? (int) $input->getOption('id') : false;

		try {
			$lang = Validators::is($input->getOption('lang'), 'string') ? Language::from($input->getOption('lang')) : null;
		} catch (Throwable $t) {
			throw new Exception(sprintf('Option "--lang" has invalid value. Allowed values are "%s"', join('/', array_map(fn(Language $l) => $l->value, Language::cases()))));
		}

		if ($isAll && is_int($id)) {
			throw new Exception('Options "all" and "id" cannot be used at the same time');
		} elseif (!$isAll && $id === false) {
			throw new Exception('At least one option must be set ("--all" or "--id")');
		} elseif (is_int($id)) {
			if ($lang !== null) {
				$this->parseResponses([$this->productIndexer->index($id, $lang)], $output);
			} else {
				foreach (Language::cases() as $case) {
					$this->parseResponses([$this->productIndexer->index($id, $case)], $output);
				}
			}
		} else {
			if ($lang !== null) {
				$this->parseResponses($this->productIndexer->indexAll($lang), $output);
			} else {
				foreach (Language::cases() as $case) {
					$this->parseResponses($this->productIndexer->indexAll($case), $output);
				}
			}
		}

		return Command::SUCCESS;
	}

	/** @param array<int, Response> $responses */
	private function parseResponses(array $responses, OutputInterface $output): void
	{
		foreach ($responses as $response) {
			if ($response->isOk()) {
				$data = $response->getData();

				if (array_key_exists('items', $data)) {
					if (!is_array($data['items'])) {
						throw new Exception('Items should be an array');
					}

					foreach ($data['items'] as $item) {
						if (!is_array($item) || !array_key_exists('index', $item)) {
							throw new Exception('Items do not have required key "index"');
						}

						$output->writeln(sprintf(
							'{"status": "%s", "message": "%s", "index": "%s", "id": "%s"}',
							$response->getStatus(),
							'ok',
							$this->getIndex($item['index']),
							$this->getId($item['index']),
						));
					}
				} else {
					$output->writeln(sprintf(
						'{"status": "%s", "message": "%s", "index": "%s", "id": "%s"}',
						$response->getStatus(),
						'ok',
						$this->getIndex($data),
						$this->getId($data),
					));
				}
			} else {
				$output->writeln(sprintf('{"status": "%s", "error": "%s"}', $response->getStatus(), $response->getErrorMessage()));
			}
		}
	}

	/** @param array<mixed> $data */
	private function getIndex(array $data): string
	{
		return $data['_index'] ?? '';
	}

	/** @param array<mixed> $data */
	private function getId(array $data): string
	{
		return $data['_id'] ?? '';
	}

}
