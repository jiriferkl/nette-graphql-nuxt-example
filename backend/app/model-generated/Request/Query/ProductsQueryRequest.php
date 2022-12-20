<?php

/**
 * This file is auto-generated.
 */

declare(strict_types=1);

namespace App\ModelGenerated\Request\Query;

use App\Model\Graphql\QueryRequest;

final readonly class ProductsQueryRequest implements QueryRequest
{
	public function __construct()
	{
	}


	/**
	 * @phpstan-param array{} $args
	 */
	public static function fromArray(array $args): self
	{
		return new self();
	}
}
