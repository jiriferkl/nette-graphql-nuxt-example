<?php declare(strict_types=1);

namespace App\Model\Graphql;

interface QueryResolver
{

	public function resolve(QueryRequest $request): mixed;

}
