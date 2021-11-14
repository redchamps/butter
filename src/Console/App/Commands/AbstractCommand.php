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
    protected $root;
    protected $phpBin;
    protected $composerBin;
    protected $startTime;

    public function __construct($config, $root, string $name = null)
    {
        $this->startTime = microtime(true);
        $this->config = $config;
        $this->root = $root;
        $this->phpBin = isset($this->config['php_bin_path'])?$this->config['php_bin_path']:"php";
        $this->composerBin = isset($this->config['composer_bin_path'])?$this->config['composer_bin_path']:"composer";
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
            ->addOption('generate-profile', null, InputOption::VALUE_OPTIONAL,'Choose if performance profile needs to be generated(small/medium/large/extra_large/n).', "n")
            ->addOption('installation-name', null, InputOption::VALUE_OPTIONAL,'Choose installation name', "null")
            ->addOption('installation-root', null, InputOption::VALUE_OPTIONAL,'Choose installation root', "null")
            ->addOption('pre-install-commands', null, InputOption::VALUE_OPTIONAL,'Comma-seperated pre-install commands', "null")
            ->addOption('post-install-commands', null, InputOption::VALUE_OPTIONAL,'Comma-seperated post-install commands', "null");
    }

    protected function postExecute($output)
    {
        $output->writeln(sprintf('<comment>Time Taken: %s minutes</comment>', $this->getTimeTaken()));
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

    protected function getTimeTaken()
    {
        $diff = microtime(true)-$this->startTime;
        return intval($diff/60).".".($diff%60);
    }

    protected function getInstallationRoot($input)
    {
        return rtrim($input->getOption("installation-root") !== "null"?$input->getOption("installation-root"):$this->config['installation_root'], "/")."/";
    }
}
