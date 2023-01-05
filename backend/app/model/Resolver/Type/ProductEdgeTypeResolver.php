<?php declare(strict_types=1);

namespace App\Model\Resolver\Type;

use App\Model\Graphql\Context;
use App\Model\Graphql\Cursor;
use App\Model\Graphql\Resolver\Type\ResolverInstance;
use App\ModelGenerated\Resolver\Type\ProductEdgeTypeResolverInterface;
use App\ModelGenerated\Resolver\Type\ProductTypeResolverInterface;
use Exception;
use GraphQL\Type\Definition\ResolveInfo;

final readonly class ProductEdgeTypeResolver implements ProductEdgeTypeResolverInterface
{

	public function __construct(private ProductTypeResolverInterface $productTypeResolver)
	{
	}

	public function resolveCursor(mixed $data, Context $context, ResolveInfo $info): Cursor
	{
		if (!is_int($data)) {
			throw new Exception();
		}

		return new Cursor($data);
	}

	public function resolveNode(mixed $data, Context $context, ResolveInfo $info): ResolverInstance
	{
		if (!is_int($data)) {
			throw new Exception();
		}

		return new ResolverInstance($this->productTypeResolver, $data);
	}

}
