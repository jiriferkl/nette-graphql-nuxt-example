<?php declare(strict_types=1);

namespace App\Model\Resolver\Type;

use App\Model\Graphql\Context;
use App\Model\Graphql\Cursor;
use App\Model\Graphql\Pagination;
use App\Model\Graphql\Resolver\Type\PageInfoResolver;
use App\Model\Graphql\Resolver\Type\ResolverInstance;
use App\ModelGenerated\Resolver\Type\ProductConnectionTypeResolverInterface;
use App\ModelGenerated\Resolver\Type\ProductEdgeTypeResolverInterface;
use Exception;
use GraphQL\Type\Definition\ResolveInfo;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Nette\Utils\Arrays;

final readonly class ProductConnectionTypeResolver implements ProductConnectionTypeResolverInterface
{

	public function __construct(private Explorer $database, private ProductEdgeTypeResolverInterface $productEdgeTypeResolver)
	{
	}

	public function resolveTotalCount(mixed $data, Context $context, ResolveInfo $info): int
	{
		return $this->database->fetchField('SELECT COUNT(id) FROM products');
	}

	/** @return array<int, ResolverInstance> */
	public function resolveEdges(mixed $data, Context $context, ResolveInfo $info): array
	{
		if (!$data instanceof Pagination) {
			throw new Exception();
		}

		return array_map(function (ActiveRow $row): ResolverInstance {
			return new ResolverInstance($this->productEdgeTypeResolver, $row->id);
		}, $this->getEdges($data));
	}

	public function resolvePageInfo(mixed $data, Context $context, ResolveInfo $info): PageInfoResolver
	{
		if (!$data instanceof Pagination) {
			throw new Exception();
		}

		/** @var ActiveRow|null $lastEdge */
		$lastEdge = Arrays::last($this->getEdges($data));

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
