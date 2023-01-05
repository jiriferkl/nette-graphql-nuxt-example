<?php declare(strict_types=1);

namespace App\Model\Graphql\Request;

interface Request
{

	/** @param array<mixed> $args */
	public static function fromArray(array $args): self;

}
