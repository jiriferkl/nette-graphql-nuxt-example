<?php declare(strict_types = 1);

namespace App\Controller;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\UI\Controller\IController;

/**
 * @Path("/")
 */
final class GraphqlController implements IController
{

	/**
	 * @Path("/")
	 * @Method("GET")
	 */
	public function index(): array
	{
		return [];
	}

}
