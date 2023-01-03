<?php declare(strict_types=1);

namespace App\Model\Command\Generator;

use App\Model\Graphql\GraphqlServer;
use App\Model\Graphql\Resolver\Type\ConnectionTypeResolverInstance;
use App\Model\Graphql\Resolver\Type\TypeResolverInstance;
use Exception;
use GraphQL\Type\Definition\IDType;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Type\Definition\Type;
use Nette\Utils\Strings;
use Symfony\Component\Console\Command\Command;

abstract class GraphqlGenerator extends Command
{

	public function __construct(protected GraphqlServer $graphqlServer)
	{
		parent::__construct();
	}

	protected function convertToPhpType(Type $type): string
	{
		if ($type instanceof IDType) {
			return 'int';
		} elseif ($type instanceof ListOfType) {
			return 'array';
		} elseif ($type instanceof ScalarType) {
			return Strings::lower($type->toString());
		} else {
			throw new Exception(sprintf('Type "%s" is not implemented', $type::class));
		}
	}

	protected function convertReturnTypeToPhpType(Type $type): string
	{
		if (Strings::endsWith($type->name, 'Connection')) {
			return ConnectionTypeResolverInstance::class;
		} elseif ($type instanceof ObjectType) {
			return TypeResolverInstance::class;
		}

		return $this->convertToPhpType($type);
	}

	protected function getProperType(Type $type): Type
	{
		if ($type instanceof NonNull) {
			$type = $type->getWrappedType();
		}

		return $type;
	}

}