<?php declare(strict_types = 1);

namespace Qa\Rule;

use App\Model\Graphql\Resolver\Query\QueryResolver;
use App\Model\Graphql\Resolver\Type\TypeResolver;
use Nette\Utils\Arrays;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Interface_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;
use Qa\ContainerResolver;

/**
 * @implements Rule<Interface_>
 */
final readonly class ResolverInterfaceShouldBeRegisteredService implements Rule
{

	public function __construct(private ContainerResolver $containerResolver)
	{
	}

	public function getNodeType(): string
	{
		return Interface_::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		if (!($node instanceof Interface_)) {
			throw new ShouldNotHappenException();
		}

		$right = Arrays::some($node->extends, function (Name $name): bool {
			return in_array($name->toString(), [TypeResolver::class, QueryResolver::class], true);
		});

		if (!$right) {
			return [];
		}

		$container = $this->containerResolver->getContainer();

		$namespacedName = $node->namespacedName;
		if ($namespacedName === null) {
			throw new ShouldNotHappenException();
		}

		/** @phpstan-var class-string $type */
		$type = $namespacedName->toString();

		if ($container->getByType($type, false) !== null) {
			return [];
		}

		$errors = [];
		$message = sprintf('Interface "%s" should be registered as service in DIC.', $node->namespacedName);
		$errors[] = RuleErrorBuilder::message($message)
			->line($node->getLine())
			->build();

		return $errors;
	}

}
