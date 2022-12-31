<?php declare(strict_types=1);

namespace App\Model\Resolver\Type;

use App\Model\Graphql\Cursor;
use App\Model\Graphql\Pagination;
use App\Model\Graphql\Resolver\Type\PageInfoResolver;
use App\Model\Graphql\Resolver\Type\TypeResolverInstance;
use App\ModelGenerated\Resolver\Type\ProductConnectionTypeResolverInterface;
use App\ModelGenerated\Resolver\Type\ProductEdgeTypeResolverInterface;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Nette\Utils\Arrays;

final readonly class ProductConnectionTypeResolver implements ProductConnectionTypeResolverInterface
{

	public function __construct(private Explorer $database, private ProductEdgeTypeResolverInterface $productEdgeTypeResolver)
	{
	}

	public function resolveTotalCount(Pagination $pagination): int
	{
		return $this->database->fetchField('SELECT COUNT(id) FROM products');
	}

	/** @return array<int, TypeResolverInstance> */
	public function resolveEdges(Pagination $pagination): array
	{
		return array_map(function (ActiveRow $row): TypeResolverInstance {
			return new TypeResolverInstance($this->productEdgeTypeResolver, $row->id);
		}, $this->getEdges($pagination));
	}

	public function resolvePageInfo(Pagination $pagination): PageInfoResolver
	{
		/** @var ActiveRow|null $lastEdge */
		$lastEdge = Arrays::last($this->getEdges($pagination));

		/** @var int|null $endCursor */
		$endCursor = $lastEdge?->id;

		$hasNextPage = false;
		if ($endCursor !== null) {
			$next = $this->database
				->table('products')
				->select('id')
				->where('id > ?', $endCursor)
				->limit(1)
				->fetch();

			$hasNextPage = $next !== null;
		}

		return new PageInfoResolver(
			endCursor: $endCursor !== null ? new Cursor(id: $endCursor) : null,
			hasNextPage: $hasNextPage,
		);
	}

	/** @return array<int, ActiveRow> */
	private function getEdges(Pagination $pagination): array
	{
		return $this->database
			->table('products')
			->select('id')
			->where('id > ?', $pagination->after)
			->limit($pagination->first)
			->fetchAll();
	}

}
