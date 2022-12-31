<?php declare(strict_types=1);

namespace App\Model\Graphql;

use App\Model\Graphql\Exception\CursorException;
use Nette\Utils\Validators;
use Stringable;

final readonly class Cursor implements Stringable
{

	public function __construct(public int $id)
	{
	}

	public function __toString(): string
	{
		return base64_encode((string) $this->id);
	}

	public static function fromString(?string $string): ?self
	{
		if ($string === null) {
			return null;
		}

		$id = base64_decode($string);
		if (!Validators::isNumericInt($id)) {
			throw new CursorException();
		}

		return new self(id: (int) $id);
	}

}
