<?php declare(strict_types=1);

namespace App\Model\Command\RabbitMQ;

use App\Model\Elastica\ProductIndexer;
use App\ModelGenerated\Enum\Language;
use Bunny\Channel;
use Bunny\Client;
use Bunny\Message;
use DateTime;
use Exception;
use Nette\Database\Explorer;
use Nette\Utils\Json;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ProductChangesIndexConsumer extends Command
{

	private const QUEUE = 'product_changes_index';
	private const MAXWELL_EXCHANGE = 'maxwell';

	protected static $defaultName = 'rabbitmq:consumer:product-changes-index';

	public function __construct(private readonly ProductIndexer $productIndexer, private readonly Explorer $database)
	{
		parent::__construct();
	}

	public function execute(InputInterface $input, OutputInterface $output): int
	{
		$client = new Client(['host' => 'rabbitmq']);
		$client->connect();

		$channel = $client->channel();
		$channel->queueDeclare(self::QUEUE);

		$keys = [
			'georgina.products',
			'georgina.products_translations',
			'georgina.studios',
		];

		foreach ($keys as $key) {
			$channel->queueBind(self::QUEUE, self::MAXWELL_EXCHANGE, $key);
		}

		$channel->consume(function (Message $message, Channel $channel, Client $client) use ($output): void {
			$content = Json::decode($message->content);
			$ids = null;

			if ($content->table === 'products') {
				$ids = [$content->data->id];
			} elseif ($content->table === 'products_translations') {
				$ids = [$content->data->product_id];
			} elseif ($content->table === 'studios') {
				$ids = $this->database
					->table('products')
					->where('studio_id = ?', $content->data->id)
					->fetchPairs('id', 'id');
			}

			if (!is_array($ids)) {
				throw new Exception(sprintf('Ids for table "%s" was not parsed', $content->table));
			}

			foreach (Language::cases() as $language) {
				foreach ($ids as $id) {
					$response = $this->productIndexer->index($id, $language);
					$output->writeln(sprintf('{"time": "%s", "status": "%s", "id": %s, "locale": "%s"}', (new DateTime())->format(DateTime::ATOM), $response->getStatus(), $id, $language->value));
				}
			}

			$channel->ack($message);
		}, self::QUEUE);

		$client->run();

		return Command::SUCCESS;
	}

}
