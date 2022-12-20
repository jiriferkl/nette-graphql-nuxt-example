<?php declare(strict_types=1);

namespace App\Model\Graphql;

use Exception;
use GraphQL\Type\Definition\ResolveInfo;
use Nette\DI\Container;
use Nette\Utils\Strings;

final readonly class GraphqlResolver
{

	public function __construct(private Container $container)
	{
	}

	public function getResolver(): callable
	{
		$buffer = new Buffer();
		return function(mixed $root, array $args, ?array $context, ResolveInfo $info) use (& $buffer): mixed {
			$name = Strings::capitalize($info->fieldName);
			if ($root === null) {
				return $this->getQueryResolver($name)->resolve($this->getQueryRequest($name, $args));
			} elseif ($root instanceof ResolverInstance) {
				return $root->resolver->{'resolve' . $name}($root->id, $buffer);
			} else {
				throw new Exception('Not implemented');
			}
		};
	}

	private function getQueryResolver(string $name): QueryResolver
	{
		/** @phpstan-var class-string $class */
		$class = sprintf("App\\ModelGenerated\\Resolver\\Query\\%sQueryResolverInterface", $name);

		/** @phpstan-var QueryResolver $resolver */
		$resolver = $this->container->getByType($class);

		return $resolver;
	}

	private function getQueryRequest(string $name, array $args): QueryRequest
	{
		/** @var QueryRequest $class */
		$class = sprintf("App\\ModelGenerated\\Request\\Query\\%sQueryRequest", $name);
		return $class::fromArray($args);
	}

}
