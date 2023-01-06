<?php declare(strict_types=1);

namespace App\Model\Elastica;

use App\ModelGenerated\Enum\Language;
use Contributte\Elastica\Client;
use Elastica\Index;

final readonly class ElasticaIndex
{

	public function __construct(private Client $client)
	{
	}

	public function createIndex(): void
	{
		foreach (Language::cases() as $language) {
			$this->getIndex($language)->create(
				[
					'settings' => [
						'number_of_shards' => 4,
						'number_of_replicas' => 1,
					]
				]
			);
		}
	}

	public function getIndex(Language $language): Index
	{
		return $this->client->getIndex(sprintf('georgina_%s', $language->value));
	}

}
