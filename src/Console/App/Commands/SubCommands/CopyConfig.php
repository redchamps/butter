<?php
namespace Console\App\Commands\SubCommands;

use Console\App\Commands\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CopyConfig extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('copy:config');
        $this->setHidden(true);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $configSource = $this->config . '/../etc/config.php';
            $newPath = $this->config. '/../../butter-config.php';
            system("cp $configSource $newPath");
            $this->runCommand("cp $configSource $newPath");
            $printablePath = realpath($newPath);
            $output->writeln(sprintf("<error>Butter is not configured yet. Please configure it from file at path $printablePath</error>"));
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
        return Self::SUCCESS;
    }
}
