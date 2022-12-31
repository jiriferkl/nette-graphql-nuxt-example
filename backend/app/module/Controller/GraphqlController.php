<?php declare(strict_types = 1);

namespace App\Module\Controller;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Exception\Api\ClientErrorException;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use Apitte\Core\UI\Controller\IController;
use App\Model\Graphql\GraphqlServer;
use Nette\Utils\JsonException;

/**
 * @Path("/")
 */
final readonly class GraphqlController implements IController
{

	public function __construct(private GraphqlServer $graphqlServer)
	{
	}

	/**
	 * @Path("/")
	 * @Method("POST")
	 * @return array<mixed>
	 */
	public function index(ApiRequest $request, ApiResponse $response): array
	{
		try {
			$body = $request->getJsonBody();
		} catch (JsonException $e) {
			throw ClientErrorException::create()
				->withCode(ApiResponse::S400_BAD_REQUEST)
				->withMessage('Body must be json');
		}

		if (!is_array($body)) {
			throw ClientErrorException::create()
				->withCode(ApiResponse::S400_BAD_REQUEST)
				->withMessage('Body must be json');
		}

		if (!array_key_exists('query', $body)) {
			throw ClientErrorException::create()
				->withCode(ApiResponse::S400_BAD_REQUEST)
				->withMessage('Missing root key \'query\'');
		}

		$query = $body['query'];
		if (!is_string($query)) {
			throw ClientErrorException::create()
				->withCode(ApiResponse::S400_BAD_REQUEST)
				->withMessage('Root key \'query\' must be string');
		}

		$variables = $body['variables'] ?? [];
		if (!is_array($variables)) {
			throw ClientErrorException::create()
				->withCode(ApiResponse::S400_BAD_REQUEST)
				->withMessage('Variables must be json');
		}

		return $this->graphqlServer->execute(query: $query, variables: $variables);
	}

}
