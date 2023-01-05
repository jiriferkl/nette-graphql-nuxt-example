<?php

/**
 * This file is auto-generated.
 */

declare(strict_types=1);

namespace App\ModelGenerated\Request\Type;

use App\Model\Graphql\Request\Request;
use App\ModelGenerated\Enum\Language;

final readonly class ProductTypeLongDescriptionFieldRequest implements Request
{
	public function __construct(public Language $lang)
	{
	}


	/**
	 * @phpstan-param array{lang: string} $args
	 */
	public static function fromArray(array $args): self
	{
		return new self(lang: Language::from($args['lang']));
	}
}
