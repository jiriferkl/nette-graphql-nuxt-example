<?php declare(strict_types = 1);

namespace App\Model\Resolver\Type;

use App\Model\Graphql\Context;
use App\ModelGenerated\Resolver\Type\StudioTypeResolverInterface;
use Exception;
use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;
use Nette\Database\Connection;

final readonly class StudioTypeResolver implements StudioTypeResolverInterface
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
				return $this->database->fetchPairs('SELECT id, id AS id1 FROM studios WHERE id in (?)', $ids);
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
				return $this->database->fetchPairs('SELECT id, name FROM studios WHERE id in (?)', $ids);
			});
		});
	}

}
