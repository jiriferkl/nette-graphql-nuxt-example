<?php

/**
 * This file is auto-generated.
 */

declare(strict_types=1);

namespace App\ModelGenerated\Resolver\Type;

use App\Model\Graphql\Context;
use App\Model\Graphql\Resolver\Type\ResolverInstance;
use App\Model\Graphql\Resolver\Type\TypeResolver;
use App\ModelGenerated\Request\Type\ProductTypeLongDescriptionFieldRequest;
use App\ModelGenerated\Request\Type\ProductTypeShortDescriptionFieldRequest;
use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;

interface ProductTypeResolverInterface extends TypeResolver
{
	public function resolveId(mixed $data, Context $context, ResolveInfo $info): int|Deferred;


	public function resolveName(mixed $data, Context $context, ResolveInfo $info): string|Deferred;


	public function resolveShortDescription(
		mixed $data,
		Context $context,
		ResolveInfo $info,
		ProductTypeShortDescriptionFieldRequest $request,
	): string|Deferred;


	public function resolveLongDescription(
		mixed $data,
		Context $context,
		ResolveInfo $info,
		ProductTypeLongDescriptionFieldRequest $request,
	): string|Deferred;


	public function resolveCurrentPrice(mixed $data, Context $context, ResolveInfo $info): string|Deferred;


	public function resolveOriginalPrice(mixed $data, Context $context, ResolveInfo $info): string|Deferred;


	public function resolveDiscount(mixed $data, Context $context, ResolveInfo $info): int|Deferred;


	public function resolveStudio(mixed $data, Context $context, ResolveInfo $info): ResolverInstance|TypeResolver|Deferred;


	public function resolveProductPlatform(
		mixed $data,
		Context $context,
		ResolveInfo $info,
	): ResolverInstance|TypeResolver|Deferred;


	public function resolveProductEdition(
		mixed $data,
		Context $context,
		ResolveInfo $info,
	): ResolverInstance|TypeResolver|Deferred;
}
