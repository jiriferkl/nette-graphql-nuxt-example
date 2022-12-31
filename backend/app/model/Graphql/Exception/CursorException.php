<?php declare(strict_types=1);

namespace App\Model\Graphql\Exception;

use GraphQL\Error\UserError;

final class CursorException extends UserError
{

	public function __construct()
	{
		parent::__construct('Cursor is invalid');
	}

}
