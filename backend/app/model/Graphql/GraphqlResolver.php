<?php declare(strict_types=1);

namespace App\Model\Graphql;

use App\Model\Graphql\Request\Request;
use App\Model\Graphql\Resolver\Query\QueryResolver;
use App\Model\Graphql\Resolver\Type\PageInfoResolver;
use App\Model\Graphql\Resolver\Type\ResolverInstance;
use Exception;
use GraphQL\Type\Definition\ResolveInfo;
use Nette\DI\Container;
use Nette\Utils\Arrays;
use Nette\Utils\Strings;
use Nette\Utils\Validators;

final readonly class GraphqlResolver
{

	public function __construct(private Container $container)
	{
	}

	public function getResolver(): callable
	{
		return function (mixed $root, array $args, Context $context, ResolveInfo $info): mixed {
			$name = Strings::firstUpper($info->fieldName);
			if ($root === null) {
				return $this->getQueryResolver($name)->resolve($this->getQueryRequest($name, $args), $context, $info);
			} elseif ($root instanceof PageInfoResolver) {
				return $root->{'resolve' . $name}();
			} elseif ($root instanceof ResolverInstance) {
				$className = (string) Arrays::last(explode('\\', $root->resolver::class));

				Validators::assert($className, 'pattern:[a-zA-Z]+TypeResolver', 'resolver method requires specific class name pattern to work,');

				return $root->resolver->{'resolve' . $name}($root->data, $context, $info, $this->getTypeRequest(Strings::substring($className, 0, -12), $name, $args));
			} else {
				throw new Exception('Not implemented');
			}
		};
	}

	private function getQueryResolver(string $name): QueryResolver
	{
		/** @phpstan-var class-string<QueryResolver> $class */
		$class = sprintf("App\\ModelGenerated\\Resolver\\Query\\%sQueryResolverInterface", $name);

		/** @phpstan-var QueryResolver $resolver */
		$resolver = $this->container->getByType($class);

		return $resolver;
	}

	/** @param array<mixed> $args */
	private function getQueryRequest(string $name, array $args): Request
	{
		/** @phpstan-var class-string<Request> $class */
		$class = sprintf("App\\ModelGenerated\\Request\\Query\\%sQueryRequest", $name);

		return $class::fromArray($args);
	}

	/** @param array<mixed> $args */
	private function getTypeRequest(string $typeName, string $fieldName, array $args): Request|null
	{
		/** @phpstan-var class-string<Request> $class */
		$class = sprintf("App\\ModelGenerated\\Request\\Type\\%sType%sFieldRequest", $typeName, $fieldName);
		if (class_exists($class)) {
			return $class::fromArray($args);
		}

		return null;
	}

}
