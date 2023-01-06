<?php declare(strict_types=1);

namespace App\Model\Elastica\Resolver;

use App\Model\Graphql\Context;
use App\Model\Graphql\Cursor;
use App\Model\Graphql\Resolver\Type\ResolverInstance;
use App\ModelGenerated\Resolver\Type\ProductEdgeTypeResolverInterface;
use Elastica\Result;
use Exception;
use GraphQL\Type\Definition\ResolveInfo;

final readonly class ProductEdgeTypeResolver implements ProductEdgeTypeResolverInterface
{

	public function resolveCursor(mixed $data, Context $context, ResolveInfo $info): Cursor
	{
		if (!$data instanceof Result) {
			throw new Exception();
		}

		return new Cursor($data->id);
	}

	public function resolveNode(mixed $data, Context $context, ResolveInfo $info): ResolverInstance
	{
		if (!$data instanceof Result) {
			throw new Exception();
		}

		return new ResolverInstance(new ProductTypeResolver(), $data);
	}

}
