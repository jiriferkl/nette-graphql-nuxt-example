<?php declare(strict_types=1);

namespace App\Model\Graphql\Resolver\Query;

use App\Model\Graphql\Request\QueryRequest;

interface QueryResolver
{

	public function resolve(QueryRequest $request): mixed;

}
