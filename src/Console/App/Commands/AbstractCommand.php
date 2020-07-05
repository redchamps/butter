<?php
namespace Console\App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class AbstractCommand extends Command
{
    protected $config;

    public function __construct($config, string $name = null)
    {
        $this->config = $config;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setDescription('Installs Magento 2 versions')
            ->setHelp('Tool to install Magento versions.')
            ->addArgument('version', InputArgument::REQUIRED, 'Pass the version.')
            ->addArgument('edition', InputArgument::OPTIONAL, 'Community/Enterprise.', "community")
            ->addArgument('sample-data', InputArgument::OPTIONAL, 'Choose if sample data needs to be installed.', "0");
    }

    protected function runCommand($command)
    {
        $process = Process::fromShellCommandline($command);
        $process->setTimeout(0);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        return $process;
    }
}
