<?php
namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DumpDeps extends Command
{
    protected static $defaultName = 'app:dump-deps';

    protected function configure()
    {
        $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Dumps class dependencies')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to dump class dependencies');
        $this
        // configure an argument
        ->addArgument('class', InputArgument::REQUIRED, 'The class you are searching for')
        // ...
    ;
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ... put here the code to run in your command

        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        $message =  $this->dumpDeps($input->getArgument('class'));
        $output->writeln($message);
        return Command::SUCCESS;
        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;
    }
    private function dumpDeps(string $search)
    {
        $search = $search . ".php";
        $search = preg_quote($search);
        $search = $search . "$";
        $search = str_replace("/", "\/", $search);

        $res = shell_exec("./vendor/phpstan/phpstan/phpstan dump-deps src");
        $res = json_decode($res, true);

        $message ="";
        foreach ($res as $class => $dependencies) {
            if (preg_match("/$search/", $class)) {
                $message .= "\n";
                $message .= "\n";
                $message .= count($dependencies) . " Classes depend on " . "\e[0;32m" . $class . "\e[0m\n";
                $message .= "\n";
                foreach ($dependencies as $dependency) {
                    $message .= "\t >> \t \e[0;34m" . $dependency . "\e[0m\n";
                }
            }
        }

        return $message;
    }
}
