<?php
namespace App;

use App\Config;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class GenerateClass extends Command
{
    protected static $defaultName = 'generate:class';

    protected function configure()
    {
        $this
        ->setDescription('generates a class')
        ->setHelp('...');
        $this
        ->addArgument('class name', InputArgument::REQUIRED, 'The name of the class');
        $this->addOption("directory", "d", InputOption::VALUE_OPTIONAL, "The directory where you want the class", Config::$DIR);
        $this->addOption("namespace", "s", InputOption::VALUE_OPTIONAL, "The namespace of the class", Config::$NAMESPACE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $isCreated =  $this->generateClass($input->getArgument('class name'), $input->getOption('directory'), $input->getOption('namespace'));
            if ($isCreated) {
                $output->writeln("Class generated");
                return Command::SUCCESS;
            }
            throw new Exception();
        } catch (Throwable $th) {
            $output->writeln("Failed to generate class");
            return Command::FAILURE;
        }
    }

    private function generateClass(string $className, string $path, string $namespace)
    {
        $className = ucwords($className);
        $namespace = $namespace ?? Config::$NAMESPACE;
        $dir = $path ?? Config::$DIR;
        $content = "<?php\n\nnamespace {$namespace};\n\nclass {$className}\n{\n\n}\n";
        $results = file_put_contents("{$dir}/{$className}.php", $content);
        if (!$results) {
            return $results;
        }
        return true;
    }
}
