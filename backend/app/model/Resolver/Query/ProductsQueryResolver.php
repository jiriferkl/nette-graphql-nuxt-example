<?php declare(strict_types=1);

namespace App\Model\Resolver\Query;

use App\Model\Graphql\Context;
use App\Model\Graphql\Cursor;
use App\Model\Graphql\Pagination;
use App\Model\Graphql\Resolver\Type\ResolverInstance;
use App\ModelGenerated\Request\Query\ProductsQueryRequest;
use App\ModelGenerated\Resolver\Query\ProductsQueryResolverInterface;
use App\ModelGenerated\Resolver\Type\ProductConnectionTypeResolverInterface;
use GraphQL\Type\Definition\ResolveInfo;

final readonly class ProductsQueryResolver implements ProductsQueryResolverInterface
{

	public function __construct(private ProductConnectionTypeResolverInterface $productConnectionTypeResolver)
	{
	}

	public function resolve(ProductsQueryRequest $request, Context $context, ResolveInfo $info): ResolverInstance
	{
		return new ResolverInstance(
			$this->productConnectionTypeResolver,
			new Pagination(
				first: $request->pagination?->first,
				after: Cursor::fromString($request->pagination?->after),
			)
		);
	}

}
