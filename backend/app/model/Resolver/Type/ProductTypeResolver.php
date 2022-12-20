<?php declare(strict_types = 1);

namespace App\Model\Resolver\Type;

use App\Model\Graphql\Buffer;
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

	public function resolveTitle(int $id, Buffer $buffer): Deferred
	{
		$type = __METHOD__;
		$buffer->add($type, $id);

		return new Deferred(function () use ($buffer, $type, $id): string {
			return $buffer->get($type, $id, function (array $ids): array {
				return $this->database->fetchPairs('SELECT id, title FROM products WHERE id IN (?)', $ids);
			});
		});
	}

}
