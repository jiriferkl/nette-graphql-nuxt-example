<?php

/**
 * This file is auto-generated.
 */

declare(strict_types=1);

namespace App\ModelGenerated\Resolver\Query;

use App\Model\Graphql\Request\QueryRequest;
use App\Model\Graphql\Resolver\Query\QueryResolver;
use App\Model\Graphql\Resolver\Type\TypeResolverInstance;

interface ProductQueryResolverInterface extends QueryResolver
{
	public function resolve(QueryRequest $request): TypeResolverInstance;
}
