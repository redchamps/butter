<?php
namespace Console\App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class AbstractCommand extends Command
{
    const SUCCESS = 0;
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
            ->addOption('edition', null, InputOption::VALUE_OPTIONAL, 'Magento edition(community/enterprise).', "community")
            ->addOption('method', null, InputOption::VALUE_OPTIONAL,'Installation method(zip/composer).', "zip")
            ->addOption('sample-data', null, InputOption::VALUE_OPTIONAL,'Choose if sample data needs to be installed(y/n).', "n")
            ->addOption('generate-profile', null, InputOption::VALUE_OPTIONAL,'Choose if performance profile needs to be generated(small/medium/large/extra_large/n).', "n");
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

    protected function vhostEnabled()
    {
        return isset($this->config['vhost']) && $this->config['vhost']['enabled'];
    }
}
