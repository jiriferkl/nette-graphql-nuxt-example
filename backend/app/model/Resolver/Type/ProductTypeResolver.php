<?php declare(strict_types = 1);

namespace App\Model\Resolver\Type;

use App\Model\Graphql\Buffer;
use App\Model\Graphql\Resolver\Type\TypeResolverInstance;
use App\ModelGenerated\Resolver\Type\ProductTypeResolverInterface;
use GraphQL\Deferred;
use Nette\Database\Connection;

final readonly class ProductTypeResolver implements ProductTypeResolverInterface
{

	public function __construct(private Connection $database)
	{
	}

	public function resolveId(int $id, Buffer $buffer): Deferred
	{
		$type = __METHOD__;
		$buffer->add($type, $id);

		return new Deferred(function () use ($buffer, $type, $id): int {
			return $buffer->get($type, $id, function (array $ids): array {
				return $this->database->fetchPairs('SELECT id, id AS id1 FROM products WHERE id IN (?)', $ids);
			});
		});
	}

	public function resolveName(int $id, Buffer $buffer): Deferred
	{
		$type = __METHOD__;
		$buffer->add($type, $id);

		return new Deferred(function () use ($buffer, $type, $id): string {
			return $buffer->get($type, $id, function (array $ids): array {
				return $this->database->fetchPairs('SELECT id, name FROM products WHERE id IN (?)', $ids);
			});
		});
	}

	public function resolveShortDescription(int $id, Buffer $buffer): Deferred
	{
		$type = __METHOD__;
		$buffer->add($type, $id);

		return new Deferred(function () use ($buffer, $type, $id): string {
			return $buffer->get($type, $id, function (array $ids): array {
				return $this->database->fetchPairs('SELECT id, short_description FROM products WHERE id IN (?)', $ids);
			});
		});
	}

	public function resolveFullDescription(int $id, Buffer $buffer): Deferred
	{
		$type = __METHOD__;
		$buffer->add($type, $id);

		return new Deferred(function () use ($buffer, $type, $id): string {
			return $buffer->get($type, $id, function (array $ids): array {
				return $this->database->fetchPairs('SELECT id, full_description FROM products WHERE id IN (?)', $ids);
			});
		});
	}

	public function resolveCurrentPrice(int $id, Buffer $buffer): Deferred
	{
		$type = __METHOD__;
		$buffer->add($type, $id);

		return new Deferred(function () use ($buffer, $type, $id): string {
			return $buffer->get($type, $id, function (array $ids): array {
				return $this->database->fetchPairs('SELECT id, CAST(current_price AS CHAR) FROM products WHERE id IN (?)', $ids);
			});
		});
	}

	public function resolveOriginalPrice(int $id, Buffer $buffer): Deferred
	{
		$type = __METHOD__;
		$buffer->add($type, $id);

		return new Deferred(function () use ($buffer, $type, $id): string {
			return $buffer->get($type, $id, function (array $ids): array {
				return $this->database->fetchPairs('SELECT id, CAST(original_price AS CHAR) FROM products WHERE id IN (?)', $ids);
			});
		});
	}

	public function resolveDiscount(int $id, Buffer $buffer): Deferred
	{
		throw new \Exception('Not implemented');
	}

	public function resolveStudio(int $id, Buffer $buffer): Deferred
	{
		throw new \Exception('Not implemented');
	}

	public function resolveProductPlatform(int $id, Buffer $buffer): Deferred
	{
		throw new \Exception('Not implemented');
	}

	public function resolveProductEdition(int $id, Buffer $buffer): Deferred
	{
		throw new \Exception('Not implemented');
	}

}
