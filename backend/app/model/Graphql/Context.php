<?php declare(strict_types=1);

namespace App\Model\Graphql;

final readonly class Context
{

	public function __construct(public Buffer $buffer = new Buffer())
	{
	}

}
