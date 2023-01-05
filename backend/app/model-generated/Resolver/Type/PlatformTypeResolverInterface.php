<?php

/**
 * This file is auto-generated.
 */

declare(strict_types=1);

namespace App\ModelGenerated\Resolver\Type;

use App\Model\Graphql\Context;
use App\Model\Graphql\Resolver\Type\TypeResolver;
use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;

interface PlatformTypeResolverInterface extends TypeResolver
{
	public function resolveId(mixed $data, Context $context, ResolveInfo $info): int|Deferred;


	public function resolveName(mixed $data, Context $context, ResolveInfo $info): string|Deferred;
}
