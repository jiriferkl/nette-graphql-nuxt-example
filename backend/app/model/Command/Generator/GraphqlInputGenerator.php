<?php declare(strict_types=1);

namespace App\Model\Command\Generator;

use Exception;
use GraphQL\Type\Definition\IDType;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;
use Nette\PhpGenerator\Literal;
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

final class GraphqlInputGenerator extends GraphqlGenerator
{

	protected static $defaultName = 'generate:input';

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
		$io->title('Input');

		$printer = new Printer();

		$baseDir = __DIR__ . '/../../../model-generated/';
		$dir = sprintf("%sInput/", $baseDir);

		$filesToRemove = [];
		foreach (Finder::findFiles('*.php')->from($dir) as $key => $file) {
			$filesToRemove[$file->getFilename()] = $key;
		}

		foreach ($this->graphqlServer->createSchema()->getTypeMap() as $type) {
			if (!($type instanceof InputObjectType)) {
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

			$namespace = $file->addNamespace('App\ModelGenerated\Input');
			$name = Strings::firstUpper($type->name) . 'Input';
			$class = $namespace->addClass($name);
			$class->setFinal();
			$class->setReadOnly();

			$constructor = $class->addMethod('__construct');
			$method = $class->addMethod('fromArray');
			$method->setStatic();
			$method->addParameter('args')->setType('array');
			$method->setReturnType('self');

			$body = [];
			$comment = [];

			foreach ($type->getFields() as $field) {
				$constructorParameter = $constructor->addPromotedParameter($field->name);

				$fieldType = $field->getType();
				$isNonNull = $fieldType instanceof NonNull;

				if ($isNonNull) {
					$fieldType = $this->getProperType($fieldType);
				} else {
					$constructorParameter->setNullable();
				}

				$body[$field->name] = new Literal(sprintf('%s$args[\'%s\']', $this->getCast($fieldType), $field->name));
				$constructorParameter->setType($this->convertToPhpType($fieldType));
				$comment[] = sprintf('%s: %s%s', $field->name, $this->convertToPhpType($fieldType), $isNonNull ? '' : '|null');
			}

			$method->addBody('return new self(...?:);', [$body]);
			$method->addComment(sprintf('@phpstan-param array{%s} $args', join(', ', $comment)));

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

	private function getCast(Type $type): string
	{
		if ($type instanceof IDType) {
			return '(int) ';
		}

		return '';
	}

}
