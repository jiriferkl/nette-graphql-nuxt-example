<?php declare(strict_types=1);

namespace App\Model\Elastica;

use App\ModelGenerated\Enum\Language;
use Elastica\Document;
use Elastica\Response;
use Exception;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;

final readonly class ProductIndexer
{

	private const INDEX_ALL_LIMIT = 500;

	public function __construct(private ElasticaIndex $index, private Explorer $database)
	{
	}

	public function index(int $id, Language $language): Response
	{
		return $this->index->getIndex($language)->addDocument($this->createDocument($this->database->table('products')->get($id), $language));
	}

	/** @return array<int, Response> */
	public function indexAll(Language $language): array
	{
		$responses = [];

		$total = $this->database->fetchField('SELECT COUNT(*) FROM products');
		$pages = $total / self::INDEX_ALL_LIMIT;

		for ($i = 0; $i < $pages; $i++) {
			$documents = [];

			foreach ($this->database->table('products')->limit(self::INDEX_ALL_LIMIT, $i) as $product) {
				$documents[] = $this->createDocument($product, $language);
			}

			$responses[] = $this->index->getIndex($language)->addDocuments($documents);
		}

		return $responses;
	}

	private function createDocument(ActiveRow $product, Language $language): Document
	{
		$translations = $product->related('products_translations')->where('locale = ?', $language->value);
		if ($translations->count() !== 1) {
			throw new Exception(sprintf('Count of products_translations of product "%s" should be exactly 1 but "%s" given', $product->id, $translations->count()));
		}

		$translation = $translations->fetch();

		return new Document((string) $product->id, [
			'id' => $product->id,
			'name' => $product->name,
			'studio' => [
				'id' => $product->studio->id,
				'name' => $product->studio->name,
			],
			'current_price' => $product->current_price,
			'original_price' => $product->original_price,
			'discount' => (int) round((1 - ($product->current_price / $product->original_price)) * 100),
			'short_description' => $translation->short_description,
			'long_description' => $translation->long_description,
		]);
	}

}
