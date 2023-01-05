<?php

/**
 * This file is auto-generated.
 */

declare(strict_types=1);

namespace App\ModelGenerated\Request\Query;

use App\Model\Graphql\Request\Request;

final readonly class SearchQueryRequest implements Request
{
	public function __construct(public string $query)
	{
	}


	/**
	 * @phpstan-param array{query: string} $args
	 */
	public static function fromArray(array $args): self
	{
		return new self(query: $args['query']);
	}
}
