<?php declare(strict_types=1);

namespace App\Model\Elastica\Resolver;

use App\Model\Graphql\Context;
use App\ModelGenerated\Resolver\Type\StudioTypeResolverInterface;
use Elastica\Result;
use Exception;
use GraphQL\Type\Definition\ResolveInfo;

final readonly class StudioTypeResolver implements StudioTypeResolverInterface
{

	public function resolveId(mixed $data, Context $context, ResolveInfo $info): int
	{
		if (!$data instanceof Result) {
			throw new Exception();
		}

		return $data->studio['id'];
	}

	public function resolveName(mixed $data, Context $context, ResolveInfo $info): string
	{
		if (!$data instanceof Result) {
			throw new Exception();
		}

		return $data->studio['name'];
	}

}
