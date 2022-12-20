<?php declare(strict_types = 1);

namespace App\Model\Resolver\Query;

use App\Model\Graphql\QueryRequest;
use App\Model\Graphql\ResolverInstance;
use App\ModelGenerated\Resolver\Query\ProductsQueryResolverInterface;
use App\ModelGenerated\Resolver\Type\ProductTypeResolverInterface;
use Nette\Database\Connection;
use Nette\Database\Row;

final readonly class ProductsQueryResolver implements ProductsQueryResolverInterface
{

	public function __construct(private Connection $database, private ProductTypeResolverInterface $productTypeResolver)
	{
	}

	public function resolve(QueryRequest $request): array
	{
		return array_map(function (Row $row): ResolverInstance {
			return new ResolverInstance($this->productTypeResolver, $row->id);
		}, $this->database->fetchAll('SELECT id FROM products'));
	}

}
