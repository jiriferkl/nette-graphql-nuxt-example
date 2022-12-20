<?php declare(strict_types=1);

namespace App\Model\Graphql;

final class Buffer
{

	/** @var array<string, array<int, int>> */
	private array $buffer = [];

	/** @var array<string, array<int, mixed>> */
	private array $result = [];

	public function add(string $type, int $id): void
	{
		$this->buffer[$type][$id] = $id;
	}

	/** @phpstan-param callable(array<int, int>): array<int, mixed> $load */
	public function get(string $type, int $id, callable $load): mixed
	{
		if (!array_key_exists($type, $this->result)) {
			$this->result[$type] = $load($this->buffer[$type]);
		}

		return $this->result[$type][$id];
	}

}
