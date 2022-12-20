<?php declare(strict_types=1);

namespace App\Model\Graphql;

use GraphQL\Error\ClientAware;
use GraphQL\GraphQL;
use GraphQL\Language\Parser;
use GraphQL\Type\Schema;
use GraphQL\Utils\BuildSchema;
use Nette\Utils\FileSystem;
use Psr\Log\LoggerInterface;

final readonly class GraphqlServer
{

	public function __construct(private GraphqlResolver $graphqlResolver, private LoggerInterface $logger)
	{
	}

	/** @return array<mixed> */
	public function execute(string $query): array
	{
		return GraphQL::executeQuery(
			schema: $this->createSchema(),
			source: $query,
			fieldResolver: $this->graphqlResolver->getResolver(),
		)
			->setErrorsHandler(function (array $errors, callable $formatter): array {
				/** @var ClientAware $error */
				foreach ($errors as $error) {
					if (!$error->isClientSafe()) {
						$this->logger->error($error);
					}
				}

				return array_map($formatter, $errors);
			})
			->toArray();
	}

	public function createSchema(): Schema
	{
		return BuildSchema::build(Parser::parse(FileSystem::read(__DIR__ . '/../../../schema.graphql')));
	}

}
