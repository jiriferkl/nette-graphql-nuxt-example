<?php declare(strict_types = 1);

namespace App\Model\Resolver\Type;

use App\Model\Graphql\Context;
use App\ModelGenerated\Request\Type\ProductTypeLongDescriptionFieldRequest;
use App\ModelGenerated\Request\Type\ProductTypeShortDescriptionFieldRequest;
use App\ModelGenerated\Resolver\Type\ProductTypeResolverInterface;
use Exception;
use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;
use Nette\Database\Connection;

final readonly class ProductTypeResolver implements ProductTypeResolverInterface
{

	public function __construct(private Connection $database)
	{
	}

	public function resolveId(mixed $id, Context $context, ResolveInfo $info): Deferred
	{
		if (!is_int($id)) {
			throw new Exception();
		}

		$type = __METHOD__;
		$context->buffer->add($type, $id);

		return new Deferred(function () use ($context, $type, $id): int {
			return $context->buffer->get($type, $id, function (array $ids): array {
				return $this->database->fetchPairs('SELECT id, id AS id1 FROM products WHERE id IN (?)', $ids);
			});
		});
	}

	public function resolveName(mixed $id, Context $context, ResolveInfo $info): Deferred
	{
		if (!is_int($id)) {
			throw new Exception();
		}

		$type = __METHOD__;
		$context->buffer->add($type, $id);

		return new Deferred(function () use ($context, $type, $id): string {
			return $context->buffer->get($type, $id, function (array $ids): array {
				return $this->database->fetchPairs('SELECT id, name FROM products WHERE id IN (?)', $ids);
			});
		});
	}

	public function resolveShortDescription(
		mixed $id,
		Context $context,
		ResolveInfo $info,
		ProductTypeShortDescriptionFieldRequest $request
	): Deferred
	{
		if (!is_int($id)) {
			throw new Exception();
		}

		$type = __METHOD__;
		$context->buffer->add($type, $id);

		return new Deferred(function () use ($context, $type, $id, $request): string {
			return $context->buffer->get($type, $id, function (array $ids) use ($request): array {
				return $this->database->fetchPairs('SELECT product_id, short_description FROM products_translations WHERE product_id IN (?) AND locale = ?', $ids, $request->lang->value);
			});
		});
	}

	public function resolveLongDescription(
		mixed $id,
		Context $context,
		ResolveInfo $info,
		ProductTypeLongDescriptionFieldRequest $request
	): Deferred
	{
		if (!is_int($id)) {
			throw new Exception();
		}

		$type = __METHOD__;
		$context->buffer->add($type, $id);

		return new Deferred(function () use ($context, $type, $id, $request): string {
			return $context->buffer->get($type, $id, function (array $ids) use ($request): array {
				return $this->database->fetchPairs('SELECT product_id, long_description FROM products_translations WHERE product_id IN (?) AND locale = ?', $ids, $request->lang->value);
			});
		});
	}

	public function resolveCurrentPrice(mixed $id, Context $context, ResolveInfo $info): Deferred
	{
		if (!is_int($id)) {
			throw new Exception();
		}

		$type = __METHOD__;
		$context->buffer->add($type, $id);

		return new Deferred(function () use ($context, $type, $id): string {
			return $context->buffer->get($type, $id, function (array $ids): array {
				return $this->database->fetchPairs('SELECT id, CAST(current_price AS CHAR) FROM products WHERE id IN (?)', $ids);
			});
		});
	}

	public function resolveOriginalPrice(mixed $id, Context $context, ResolveInfo $info): Deferred
	{
		if (!is_int($id)) {
			throw new Exception();
		}

		$type = __METHOD__;
		$context->buffer->add($type, $id);

		return new Deferred(function () use ($context, $type, $id): string {
			return $context->buffer->get($type, $id, function (array $ids): array {
				return $this->database->fetchPairs('SELECT id, CAST(original_price AS CHAR) FROM products WHERE id IN (?)', $ids);
			});
		});
	}

	public function resolveDiscount(mixed $id, Context $context, ResolveInfo $info): Deferred
	{
		throw new Exception('Not implemented');
	}

	public function resolveStudio(mixed $id, Context $context, ResolveInfo $info): Deferred
	{
		throw new Exception('Not implemented');
	}

	public function resolveProductPlatform(mixed $id, Context $context, ResolveInfo $info): Deferred
	{
		throw new Exception('Not implemented');
	}

	public function resolveProductEdition(mixed $id, Context $context, ResolveInfo $info): Deferred
	{
		throw new Exception('Not implemented');
	}

}
