<?php declare(strict_types=1);

namespace App\Model\Graphql\Resolver\Type;

use App\Model\Graphql\Cursor;

final readonly class PageInfoResolver implements TypeResolver
{

	public function __construct(private ?Cursor $endCursor, private bool $hasNextPage)
	{
	}

	public function resolveEndCursor(): ?Cursor
	{
		return $this->endCursor;
	}

	public function resolveHasNextPage(): bool
	{
		return $this->hasNextPage;
	}

}
