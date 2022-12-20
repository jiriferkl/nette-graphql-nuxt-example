<?php

/**
 * This file is auto-generated.
 */

declare(strict_types=1);

namespace App\ModelGenerated\Request\Query;

use App\Model\Graphql\QueryRequest;

final readonly class ProductQueryRequest implements QueryRequest
{
	public function __construct(public int $id)
	{
	}


	/**
	 * @phpstan-param array{id: int} $args
	 */
	public static function fromArray(array $args): self
	{
		return new self(id: (int) $args['id']);
	}
}
