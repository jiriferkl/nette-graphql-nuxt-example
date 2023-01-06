<?php declare(strict_types=1);

namespace App\Model\Elastica\Resolver;

use App\Model\Graphql\Context;
use App\Model\Graphql\Resolver\Type\PageInfoResolver;
use App\Model\Graphql\Resolver\Type\ResolverInstance;
use App\ModelGenerated\Resolver\Type\ProductConnectionTypeResolverInterface;
use Elastica\Result;
use Elastica\Search;
use Exception;
use GraphQL\Type\Definition\ResolveInfo;

final readonly class ProductConnectionTypeResolver implements ProductConnectionTypeResolverInterface
{

	public function resolveTotalCount(mixed $data, Context $context, ResolveInfo $info): int
	{
		if (!$data instanceof Search) {
			throw new Exception();
		}

		return $data->count();
	}

	public function resolveEdges(mixed $data, Context $context, ResolveInfo $info): array
	{
		if (!$data instanceof Search) {
			throw new Exception();
		}

		$resolver = new ProductEdgeTypeResolver();

		return array_map(function (Result $product) use ($resolver): ResolverInstance {
			return new ResolverInstance(
				$resolver,
				$product
			);
		}, iterator_to_array($data->search()));
	}

	public function resolvePageInfo(mixed $data, Context $context, ResolveInfo $info,): PageInfoResolver
	{
		throw new Exception('Not implemented');
	}

}
