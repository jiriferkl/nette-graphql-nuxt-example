<?php declare(strict_types=1);

namespace App\Model\Resolver\Query;

use App\Model\Graphql\Exception\NotFoundException;
use App\Model\Graphql\Request\QueryRequest;
use App\Model\Graphql\Resolver\Type\TypeResolverInstance;
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

	public function resolve(QueryRequest $request): TypeResolverInstance
	{
		if (!($request instanceof ProductQueryRequest)) {
			throw new Exception();
		}

		if ($this->database->fetch('SELECT id FROM products WHERE id = ?', $request->id) === null) {
			throw new NotFoundException();
		}

		return new TypeResolverInstance($this->productTypeResolver, $request->id);
	}

}
