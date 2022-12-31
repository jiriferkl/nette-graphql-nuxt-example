<?php

/**
 * This file is auto-generated.
 */

declare(strict_types=1);

namespace App\ModelGenerated\Resolver\Type;

use App\Model\Graphql\Buffer;
use App\Model\Graphql\Cursor;
use App\Model\Graphql\Resolver\Type\TypeResolver;
use App\Model\Graphql\Resolver\Type\TypeResolverInstance;
use GraphQL\Deferred;

interface ProductEdgeTypeResolverInterface extends TypeResolver
{
	public function resolveCursor(int $id, Buffer $buffer): Cursor|Deferred;


	public function resolveNode(int $id, Buffer $buffer): TypeResolverInstance|Deferred;
}
