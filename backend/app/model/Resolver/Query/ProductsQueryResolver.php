<?php declare(strict_types=1);

namespace App\Model\Resolver\Query;

use App\Model\Graphql\Cursor;
use App\Model\Graphql\Pagination;
use App\Model\Graphql\Request\QueryRequest;
use App\Model\Graphql\Resolver\Type\ConnectionTypeResolverInstance;
use App\ModelGenerated\Request\Query\ProductsQueryRequest;
use App\ModelGenerated\Resolver\Query\ProductsQueryResolverInterface;
use App\ModelGenerated\Resolver\Type\ProductConnectionTypeResolverInterface;
use Exception;

final readonly class ProductsQueryResolver implements ProductsQueryResolverInterface
{

	public function __construct(private ProductConnectionTypeResolverInterface $productConnectionTypeResolver)
	{
	}

	public function resolve(QueryRequest $request): ConnectionTypeResolverInstance
	{
		if (!$request instanceof ProductsQueryRequest) {
			throw new Exception();
		}

		return new ConnectionTypeResolverInstance(
			$this->productConnectionTypeResolver,
			new Pagination(
				first: $request->pagination?->first,
				after: Cursor::fromString($request->pagination?->after),
			)
		);
	}

}
