<?php

/**
 * This file is auto-generated.
 */

declare(strict_types=1);

namespace App\ModelGenerated\Resolver\Type;

use App\Model\Graphql\Context;
use App\Model\Graphql\Cursor;
use App\Model\Graphql\Resolver\Type\ResolverInstance;
use App\Model\Graphql\Resolver\Type\TypeResolver;
use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;

interface ProductEdgeTypeResolverInterface extends TypeResolver
{
	public function resolveCursor(mixed $data, Context $context, ResolveInfo $info): Cursor|Deferred;


	public function resolveNode(mixed $data, Context $context, ResolveInfo $info): ResolverInstance|TypeResolver|Deferred;
}
