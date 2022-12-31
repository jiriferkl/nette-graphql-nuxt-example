<?php declare(strict_types=1);

namespace App\Model\Graphql;

final class Pagination
{

	public int $first;
	public int $after;

	public function __construct(?int $first, ?Cursor $after)
	{
		$this->first = $first === null ? 10 : $first;
		$this->after = $after === null ? 0 : $after->id;
	}

}
