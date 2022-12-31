<?php declare(strict_types=1);

namespace App\Model\Graphql\Resolver\Type;

use App\Model\Graphql\Pagination;

final readonly class ConnectionTypeResolverInstance
{

	public function __construct(public ConnectionTypeResolver $resolver, public Pagination $pagination)
	{
	}

}
