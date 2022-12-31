<?php declare(strict_types=1);

namespace App\Model\Resolver\Type;

use App\Model\Graphql\Buffer;
use App\Model\Graphql\Cursor;
use App\Model\Graphql\Resolver\Type\TypeResolverInstance;
use App\ModelGenerated\Resolver\Type\ProductEdgeTypeResolverInterface;
use App\ModelGenerated\Resolver\Type\ProductTypeResolverInterface;

final readonly class ProductEdgeTypeResolver implements ProductEdgeTypeResolverInterface
{

	public function __construct(private ProductTypeResolverInterface $productTypeResolver)
	{
	}

	public function resolveCursor(int $id, Buffer $buffer): Cursor
	{
		return new Cursor($id);
	}

	public function resolveNode(int $id, Buffer $buffer): TypeResolverInstance
	{
		return new TypeResolverInstance($this->productTypeResolver, $id);
	}

}
