<?php declare(strict_types=1);

namespace App\Model\Elastica\Resolver;

use App\Model\Graphql\Context;
use App\Model\Graphql\Resolver\Type\ResolverInstance;
use App\ModelGenerated\Request\Type\ProductTypeLongDescriptionFieldRequest;
use App\ModelGenerated\Request\Type\ProductTypeShortDescriptionFieldRequest;
use App\ModelGenerated\Resolver\Type\ProductTypeResolverInterface;
use Elastica\Result;
use Exception;
use GraphQL\Type\Definition\ResolveInfo;

final readonly class ProductTypeResolver implements ProductTypeResolverInterface
{

	public function resolveId(mixed $data, Context $context, ResolveInfo $info): int
	{
		if (!$data instanceof Result) {
			throw new Exception();
		}

		return $data->id;
	}

	public function resolveName(mixed $data, Context $context, ResolveInfo $info): string
	{
		if (!$data instanceof Result) {
			throw new Exception();
		}

		return $data->name;
	}

	public function resolveShortDescription(mixed $data, Context $context, ResolveInfo $info, ProductTypeShortDescriptionFieldRequest $request): string
	{
		if (!$data instanceof Result) {
			throw new Exception();
		}

		return $data->short_description;
	}

	public function resolveLongDescription(mixed $data, Context $context, ResolveInfo $info, ProductTypeLongDescriptionFieldRequest $request): string
	{
		if (!$data instanceof Result) {
			throw new Exception();
		}

		return $data->long_description;
	}

	public function resolveCurrentPrice(mixed $data, Context $context, ResolveInfo $info): string
	{
		if (!$data instanceof Result) {
			throw new Exception();
		}

		return (string) $data->current_price;
	}

	public function resolveOriginalPrice(mixed $data, Context $context, ResolveInfo $info): string
	{
		if (!$data instanceof Result) {
			throw new Exception();
		}

		return (string) $data->original_price;
	}

	public function resolveDiscount(mixed $data, Context $context, ResolveInfo $info): int
	{
		if (!$data instanceof Result) {
			throw new Exception();
		}

		return $data->discount;
	}

	public function resolveStudio(mixed $data, Context $context, ResolveInfo $info): ResolverInstance
	{
		if (!$data instanceof Result) {
			throw new Exception();
		}

		return new ResolverInstance(new StudioTypeResolver(), $data);
	}

	public function resolveProductPlatform(mixed $data, Context $context, ResolveInfo $info,): ResolverInstance
	{
		if (!$data instanceof Result) {
			throw new Exception();
		}

		throw new Exception('Not implemented');
	}

	public function resolveProductEdition(mixed $data, Context $context, ResolveInfo $info,): ResolverInstance
	{
		if (!$data instanceof Result) {
			throw new Exception();
		}

		throw new Exception('Not implemented');
	}

}
