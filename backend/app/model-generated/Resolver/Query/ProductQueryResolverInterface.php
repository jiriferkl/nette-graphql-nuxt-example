<?php

/**
 * This file is auto-generated.
 */

declare(strict_types=1);

namespace App\ModelGenerated\Resolver\Query;

use App\Model\Graphql\QueryRequest;
use App\Model\Graphql\QueryResolver;
use App\Model\Graphql\ResolverInstance;

interface ProductQueryResolverInterface extends QueryResolver
{
	public function resolve(QueryRequest $request): ResolverInstance;
}
