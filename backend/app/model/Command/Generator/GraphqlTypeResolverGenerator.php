<?php declare(strict_types=1);

namespace App\Model\Command\Generator;

use App\Model\Graphql\Buffer;
use App\Model\Graphql\ResolverInstance;
use App\Model\Graphql\TypeResolver;
use Exception;
use GraphQL\Deferred;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\Printer;
use Nette\Utils\FileSystem;
use Nette\Utils\Finder;
use Nette\Utils\Strings;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class GraphqlTypeResolverGenerator extends GraphqlGenerator
{

	protected static $defaultName = 'generate:type:resolver';

	private const EXCLUDED_TYPES = [
		'Query',
	];

	protected function configure(): void
	{
		$this->addOption(
			name: 'debug',
			shortcut: 'd',
			description: 'Result is not saved and only printed to console'
		);
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		if (!$output instanceof ConsoleOutputInterface) {
			throw new Exception('This command accepts only an instance of "ConsoleOutputInterface".');
		}

		$debug = (bool)$input->getOption('debug');

		$io = new SymfonyStyle($input, $output);
		$io->title('Type resolver');

		$printer = new Printer();

		$baseDir = __DIR__ . '/../../../model-generated/';
		$dir = sprintf("%sResolver/Type/", $baseDir);

		$filesToRemove = [];
		foreach (Finder::findFiles('*.php')->from($dir) as $key => $file) {
			$filesToRemove[$file->getFilename()] = $key;
		}

		foreach ($this->graphqlServer->createSchema()->getTypeMap() as $type) {
			if (!($type instanceof ObjectType)) {
				continue;
			}

			if ($type::isBuiltInType($type)) {
				continue;
			}

			if (in_array($type->name, self::EXCLUDED_TYPES, true)) {
				continue;
			}

			$section = $output->section();

			if ($debug) {
				$io->section(sprintf('Generating "%s"', $type->name));
			} else {
				$section->writeln(sprintf('Generating "%s" ⌛', $type->name));
			}

			$file = new PhpFile();
			$file->addComment('This file is auto-generated.');
			$file->setStrictTypes();

			$namespace = $file->addNamespace('App\ModelGenerated\Resolver\Type');
			$name = Strings::firstUpper($type->name) . 'TypeResolverInterface';
			$interface = $namespace->addInterface($name);
			$interface->addExtend(TypeResolver::class);
			$namespace->addUse(TypeResolver::class);
			$namespace->addUse(Buffer::class);
			$namespace->addUse(Deferred::class);

			foreach ($type->getFields() as $resolver) {
				$method = $interface->addMethod(sprintf('resolve%s', Strings::capitalize($resolver->getName())));
				$method->setPublic();
				$method->addParameter('id')->setType('int');
				$method->addParameter('buffer')->setType(Buffer::class);

				$resolverType = $resolver->getType();
				if ($resolverType instanceof NonNull) {
					$resolverType = $this->getProperType($resolverType);
				} else {
					$method->setReturnNullable();
				}

				$phpResolverType = $this->convertReturnTypeToPhpType($resolverType);
				if ($phpResolverType === ResolverInstance::class) {
					$namespace->addUse(ResolverInstance::class);
				}

				$method->setReturnType(sprintf('%s|%s', $phpResolverType, Deferred::class));
			}

			$printed = $printer->printFile($file);
			unset($filesToRemove[sprintf('%s.php', $name)]);

			if ($debug) {
				$io->writeln($printed);
			} else {
				FileSystem::write(sprintf("%s%s.php", $dir, $name), $printed);
				$section->overwrite(sprintf('Generating "%s" ✅', $type->name));
			}
		}

		if ($debug && count($filesToRemove) > 0) {
			$io->section('Cleaning up');
		}

		foreach ($filesToRemove as $fileName => $file) {
			if ($debug) {
				$io->writeln(sprintf('File "%s" would be deleted', $fileName));
			} else {
				$output->writeln(sprintf('Removing unnecessary "%s"', $fileName));
				FileSystem::delete($file);
			}
		}

		$io->writeln('');
		$io->success('Done');
		return Command::SUCCESS;
	}

}
