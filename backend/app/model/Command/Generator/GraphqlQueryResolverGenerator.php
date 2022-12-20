<?php declare(strict_types=1);

namespace App\Model\Command\Generator;

use App\Model\Graphql\QueryRequest;
use App\Model\Graphql\QueryResolver;
use App\Model\Graphql\ResolverInstance;
use Exception;
use GraphQL\Type\Definition\NonNull;
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

final class GraphqlQueryResolverGenerator extends GraphqlGenerator
{

	protected static $defaultName = 'generate:query:resolver';

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

		$debug = (bool) $input->getOption('debug');

		$io = new SymfonyStyle($input, $output);
		$io->title('Query resolver');

		$printer = new Printer();

		$baseDir = __DIR__ . '/../../../model-generated/';
		$dir = sprintf("%sResolver/Query/", $baseDir);

		$filesToRemove = [];
		foreach (Finder::findFiles('*.php')->from($dir) as $key => $file) {
			$filesToRemove[$file->getFilename()] = $key;
		}

		foreach ($this->graphqlServer->createSchema()->getQueryType()->getFields() as $field) {
			$section = $output->section();

			if ($debug) {
				$io->section(sprintf('Generating "%s"', $field->getName()));
			} else {
				$section->writeln(sprintf('Generating "%s" ⌛', $field->getName()));
			}

			$file = new PhpFile();
			$file->addComment('This file is auto-generated.');
			$file->setStrictTypes();

			$namespace = $file->addNamespace('App\ModelGenerated\Resolver\Query');
			$name = Strings::firstUpper($field->getName()) . 'QueryResolverInterface';
			$interface = $namespace->addInterface($name);
			$interface->addExtend(QueryResolver::class);
			$namespace->addUse(QueryResolver::class);

			$method = $interface->addMethod('resolve')->setPublic();
			$method->addParameter('request')->setType(QueryRequest::class);
			$namespace->addUse(QueryRequest::class);

			$type = $field->getType();
			if ($type instanceof NonNull) {
				$type = $this->getProperType($type);
			} else {
				$method->setReturnNullable();
			}

			$phpType = $this->convertReturnTypeToPhpType($type);
			if ($phpType === ResolverInstance::class) {
				$namespace->addUse(ResolverInstance::class);
			}

			$method->setReturnType($phpType);

			$printed = $printer->printFile($file);
			unset($filesToRemove[sprintf('%s.php', $name)]);

			if ($debug) {
				$io->writeln($printed);
			} else {
				FileSystem::write(sprintf("%s%s.php", $dir, $name), $printed);
				$section->overwrite(sprintf('Generating "%s" ✅', $field->getName()));
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
