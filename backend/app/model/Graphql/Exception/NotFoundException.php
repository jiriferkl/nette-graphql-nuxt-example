<?php declare(strict_types=1);

namespace App\Model\Graphql\Exception;

use GraphQL\Error\UserError;

final class NotFoundException extends UserError
{

	public function __construct()
	{
		parent::__construct('Not found');
	}

}
