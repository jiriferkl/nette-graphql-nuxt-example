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

interface ProductTypeResolverInterface extends TypeResolver
{
	public function resolveId(int $id, Buffer $buffer): int|Deferred;


	public function resolveName(int $id, Buffer $buffer): string|Deferred;


	public function resolveShortDescription(int $id, Buffer $buffer): string|Deferred;


	public function resolveFullDescription(int $id, Buffer $buffer): string|Deferred;


	public function resolveCurrentPrice(int $id, Buffer $buffer): string|Deferred;


	public function resolveOriginalPrice(int $id, Buffer $buffer): string|Deferred;


	public function resolveDiscount(int $id, Buffer $buffer): int|Deferred;


	public function resolveStudio(int $id, Buffer $buffer): TypeResolverInstance|Deferred;


	public function resolveProductPlatform(int $id, Buffer $buffer): TypeResolverInstance|Deferred;


	public function resolveProductEdition(int $id, Buffer $buffer): TypeResolverInstance|Deferred;
}
