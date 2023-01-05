<?php

/**
 * This file is auto-generated.
 */

declare(strict_types=1);

namespace App\ModelGenerated\Input;

final readonly class PaginationInput
{
	public function __construct(
		public ?int $first,
		public ?string $after,
	) {
	}


	/**
	 * @phpstan-param array{first?: int|null, after?: string|null} $args
	 */
	public static function fromArray(array $args): self
	{
		return new self(
			first: array_key_exists('first', $args) && !empty($args['first']) ? $args['first'] : null,
			after: array_key_exists('after', $args) && !empty($args['after']) ? $args['after'] : null,
		);
	}
}
