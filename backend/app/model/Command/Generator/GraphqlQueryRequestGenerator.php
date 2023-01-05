<?php declare(strict_types=1);

namespace App\Model\Command\Generator;

use App\Model\Graphql\Request\Request;
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

final class GraphqlQueryRequestGenerator extends GraphqlGenerator
{

	protected static $defaultName = 'generate:query:request';

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
		$io->title('Query request');

		$printer = new Printer();

		$baseDir = __DIR__ . '/../../../model-generated/';
		$dir = sprintf("%sRequest/Query/", $baseDir);

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

			$namespace = $file->addNamespace('App\ModelGenerated\Request\Query');
			$name = Strings::firstUpper($field->getName()) . 'QueryRequest';
			$class = $namespace->addClass($name);
			$class->setFinal();
			$class->setReadOnly();
			$class->addImplement(Request::class);
			$namespace->addUse(Request::class);

			$constructor = $class->addMethod('__construct');
			$method = $class->addMethod('fromArray');
			$method->setStatic();
			$method->addParameter('args')->setType('array');
			$method->setReturnType('self');
			$body = [];
			$comment = [];

			foreach ($field->args as $arg) {
				$constructorParameter = $constructor->addPromotedParameter($arg->name);

				$type = $arg->getType();
				$isNonNull = $type instanceof NonNull;

				if ($isNonNull) {
					$type = $this->getProperType($type);
				} else {
					$constructorParameter->setNullable();
				}

				if ($type instanceof InputObjectType) {
					if ($isNonNull) {
						$body[$arg->name] = new Literal(sprintf('%sInput::fromArray($args[\'%s\'])', Strings::firstUpper($arg->name), $arg->name));
					} else {
						$body[$arg->name] = new Literal(sprintf(
							'array_key_exists(\'%s\', $args) && !empty($args[\'%s\']) ? %sInput::fromArray($args[\'%s\']) : null',
							$arg->name,
							$arg->name,
							Strings::firstUpper($arg->name),
							$arg->name,
						));
					}

					$inputType = sprintf('\App\ModelGenerated\Input\%sInput', Strings::firstUpper($arg->name));
					$namespace->addUse($inputType);
					$constructorParameter->setType($inputType);

					$typeFieldComment = [];
					foreach ($type->getFields() as $typeField) {
						$typeFieldComment[] = sprintf(
							'%s%s: %s%s',
							$typeField->name,
							$typeField->getType() instanceOf NonNull ? '' : '?',
							$this->convertToPhpTypeForComment($this->getProperType($typeField->getType())),
							$typeField->getType() instanceOf NonNull ? '' : '|null'
						);
					}
					$comment[] = sprintf('%s%s: array{%s}%s', $arg->name, $isNonNull ? '' : '?', join(', ', $typeFieldComment), $isNonNull ? '' : '|null');
				} else {
					if ($isNonNull) {
						$body[$arg->name] = new Literal(sprintf('%s$args[\'%s\']', $this->getCast($type), $arg->name));
					} else {
						$body[$arg->name] = new Literal(sprintf(
							'array_key_exists(\'%s\', $args) && !empty($args[\'%s\']) ? %s%s$args[\'%s\']%s : null',
							$arg->name,
							$arg->name,
							($this->getCast($type) !== '' ? '(' : ''),
							$this->getCast($type),
							$arg->name,
							($this->getCast($type) !== '' ? ')' : '')
						));
					}

					$constructorParameter->setType($this->convertToPhpType($type));
					$comment[] = sprintf('%s%s: %s%s', $arg->name, $isNonNull ? '' : '?', $this->convertToPhpTypeForComment($type), $isNonNull ? '' : '|null');
				}
			}

			$method->addBody('return new self(...?:);', [$body]);
			$method->addComment(sprintf('@phpstan-param array{%s} $args', join(', ', $comment)));

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

	private function getCast(Type $type): string
	{
		if ($type instanceof IDType) {
			return '(int) ';
		}

		return '';
	}

}
