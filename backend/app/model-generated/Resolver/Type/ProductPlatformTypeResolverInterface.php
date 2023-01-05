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

interface ProductPlatformTypeResolverInterface extends TypeResolver
{
	public function resolveId(mixed $data, Context $context, ResolveInfo $info): int|Deferred;


	public function resolveProduct(
		mixed $data,
		Context $context,
		ResolveInfo $info,
	): ResolverInstance|TypeResolver|Deferred;


	public function resolvePlatform(
		mixed $data,
		Context $context,
		ResolveInfo $info,
	): ResolverInstance|TypeResolver|Deferred;
}
