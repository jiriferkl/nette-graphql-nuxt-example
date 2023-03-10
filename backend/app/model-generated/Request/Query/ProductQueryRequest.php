<?php

/**
 * This file is auto-generated.
 */

declare(strict_types=1);

namespace App\ModelGenerated\Request\Query;

use App\Model\Graphql\Request\Request;

final readonly class ProductQueryRequest implements Request
{
	public function __construct(public int $id)
	{
	}


	/**
	 * @phpstan-param array{id: string} $args
	 */
	public static function fromArray(array $args): self
	{
		return new self(id: (int) $args['id']);
	}
}
