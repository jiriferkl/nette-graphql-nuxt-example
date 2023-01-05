<?php

/**
 * This file is auto-generated.
 */

declare(strict_types=1);

namespace App\ModelGenerated\Resolver\Query;

use App\Model\Graphql\Context;
use App\Model\Graphql\Resolver\Query\QueryResolver;
use App\Model\Graphql\Resolver\Type\ResolverInstance;
use App\Model\Graphql\Resolver\Type\TypeResolver;
use App\ModelGenerated\Request\Query\SearchQueryRequest;
use GraphQL\Type\Definition\ResolveInfo;

interface SearchQueryResolverInterface extends QueryResolver
{
	public function resolve(
		SearchQueryRequest $request,
		Context $context,
		ResolveInfo $info,
	): ResolverInstance|TypeResolver;
}
