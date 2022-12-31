<?php declare(strict_types=1);

namespace App\Model\Graphql\Resolver\Type;

final readonly class TypeResolverInstance
{

	public function __construct(public TypeResolver $resolver, public int $id)
	{
	}

}
