<?php

/**
 * This file is auto-generated.
 */

declare(strict_types=1);

namespace App\ModelGenerated\Request\Query;

use App\Model\Graphql\Request\Request;
use App\ModelGenerated\Input\PaginationInput;

final readonly class ProductsQueryRequest implements Request
{
	public function __construct(public ?PaginationInput $pagination)
	{
	}


	/**
	 * @phpstan-param array{pagination?: array{first?: int|null, after?: string|null}|null} $args
	 */
	public static function fromArray(array $args): self
	{
		return new self(pagination: array_key_exists('pagination', $args) && !empty($args['pagination']) ? PaginationInput::fromArray($args['pagination']) : null);
	}
}
