<?php declare(strict_types=1);

namespace App\Model\Graphql\Resolver\Type;

use App\Model\Graphql\Pagination;

interface ConnectionTypeResolver extends TypeResolver
{

	public function resolveTotalCount(Pagination $pagination): int;

	public function resolveEdges(Pagination $pagination): array;

	public function resolvePageInfo(Pagination $pagination): PageInfoResolver;

}
