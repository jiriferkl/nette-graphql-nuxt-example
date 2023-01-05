<?php declare(strict_types=1);

namespace App\Model\Resolver\Query;

use App\Model\Graphql\Context;
use App\Model\Graphql\Exception\NotFoundException;
use App\Model\Graphql\Resolver\Type\ResolverInstance;
use App\ModelGenerated\Request\Query\ProductQueryRequest;
use App\ModelGenerated\Resolver\Query\ProductQueryResolverInterface;
use App\ModelGenerated\Resolver\Type\ProductTypeResolverInterface;
use GraphQL\Type\Definition\ResolveInfo;
use Nette\Database\Connection;

final readonly class ProductQueryResolver implements ProductQueryResolverInterface
{

	public function __construct(private Connection $database, private ProductTypeResolverInterface $productTypeResolver)
	{
	}

	public function resolve(ProductQueryRequest $request, Context $context, ResolveInfo $info): ResolverInstance
	{
		if ($this->database->fetch('SELECT id FROM products WHERE id = ?', $request->id) === null) {
			throw new NotFoundException();
		}

		return new ResolverInstance($this->productTypeResolver, $request->id);
	}

}
