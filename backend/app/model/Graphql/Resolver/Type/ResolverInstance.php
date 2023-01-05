<?php declare(strict_types=1);

namespace App\Model\Graphql\Resolver\Type;

final readonly class ResolverInstance
{

	public function __construct(public TypeResolver $resolver, public mixed $data = null)
	{
	}

}
