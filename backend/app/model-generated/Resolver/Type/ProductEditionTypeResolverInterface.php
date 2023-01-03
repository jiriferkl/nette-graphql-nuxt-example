<?php

/**
 * This file is auto-generated.
 */

declare(strict_types=1);

namespace App\ModelGenerated\Resolver\Type;

use App\Model\Graphql\Buffer;
use App\Model\Graphql\Resolver\Type\TypeResolver;
use App\Model\Graphql\Resolver\Type\TypeResolverInstance;
use GraphQL\Deferred;

interface ProductEditionTypeResolverInterface extends TypeResolver
{
	public function resolveId(int $id, Buffer $buffer): int|Deferred;


	public function resolveName(int $id, Buffer $buffer): string|Deferred;


	public function resolveProduct(int $id, Buffer $buffer): TypeResolverInstance|Deferred;
}
