<?php declare(strict_types=1);

namespace App\Model\Graphql\Request;

interface QueryRequest
{

	public static function fromArray(array $args): self;

}
