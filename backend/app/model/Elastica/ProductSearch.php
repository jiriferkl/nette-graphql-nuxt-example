<?php declare(strict_types=1);

namespace App\Model\Elastica;

use App\ModelGenerated\Enum\Language;
use Contributte\Elastica\Client;
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;
use Elastica\Query\Wildcard;
use Elastica\Search;

final readonly class ProductSearch
{

	public function __construct(private Client $client, private ElasticaIndex $elasticaIndex)
	{
	}

	public function search(string $query, Language $language): Search
	{
		$fuzzyMultiMatch = new MultiMatch();
		$fuzzyMultiMatch->setQuery($query);
		$fuzzyMultiMatch->setFields(['name', 'studio.name']);
		$fuzzyMultiMatch->setType('best_fields');
		$fuzzyMultiMatch->setFuzziness(2);

		$prefixMultiMatch = new MultiMatch();
		$prefixMultiMatch->setQuery($query);
		$prefixMultiMatch->setFields(['name', 'studio.name']);
		$prefixMultiMatch->setType('phrase_prefix');

		$suffixNameMatch = new Wildcard('name', '*' . $query);
		$suffixStudioNameMatch = new Wildcard('studio.name', '*' . $query);

		$boolQuery = new BoolQuery();
		$boolQuery->addShould($fuzzyMultiMatch);
		$boolQuery->addShould($prefixMultiMatch);
		$boolQuery->addShould($suffixNameMatch);
		$boolQuery->addShould($suffixStudioNameMatch);

		return (new Search($this->client))
			->addIndex($this->elasticaIndex->getIndex($language))
			->setQuery($boolQuery);
	}

}
