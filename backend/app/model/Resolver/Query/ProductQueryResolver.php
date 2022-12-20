<?php declare(strict_types=1);

namespace App\Model\Resolver\Query;

use App\Model\Graphql\NotFoundException;
use App\Model\Graphql\QueryRequest;
use App\Model\Graphql\ResolverInstance;
use App\ModelGenerated\Request\Query\ProductQueryRequest;
use App\ModelGenerated\Resolver\Query\ProductQueryResolverInterface;
use App\ModelGenerated\Resolver\Type\ProductTypeResolverInterface;
use Exception;
use Nette\Database\Connection;

final readonly class ProductQueryResolver implements ProductQueryResolverInterface
{

	public function __construct(private Connection $database, private ProductTypeResolverInterface $productTypeResolver)
	{
	}

	public function resolve(QueryRequest $request): ResolverInstance
	{
		if (!($request instanceof ProductQueryRequest)) {
			throw new Exception();
		}

		if ($this->database->fetch('SELECT id FROM products WHERE id = ?', $request->id) === null) {
			throw new NotFoundException();
		}

		return new ResolverInstance($this->productTypeResolver, $request->id);
	}

}
