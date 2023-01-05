<?php

/**
 * This file is auto-generated.
 */

declare(strict_types=1);

namespace App\ModelGenerated\Resolver\Type;

use App\Model\Graphql\Context;
use App\Model\Graphql\Resolver\Type\ResolverInstance;
use App\Model\Graphql\Resolver\Type\TypeResolver;
use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;

interface ProductConnectionTypeResolverInterface extends TypeResolver
{
	public function resolveTotalCount(mixed $data, Context $context, ResolveInfo $info): int|Deferred;


	public function resolveEdges(mixed $data, Context $context, ResolveInfo $info): array|Deferred;


	public function resolvePageInfo(
		mixed $data,
		Context $context,
		ResolveInfo $info,
	): ResolverInstance|TypeResolver|Deferred;
}
