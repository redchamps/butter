<?php
namespace Console\App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class MagentoInstall extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('magento:install');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $version = $input->getArgument('version');
        $output->writeln(
            sprintf('<info>Installing Magento %s version %s</info>',
                $input->getArgument('edition'), $version
            )
        );
        $installationName = str_replace([".", "-"], ["", ""], $version);
        $arguments = $input->getArguments();
        $arguments['installation-name'] = $input->getArgument("edition") == "enterprise"?$installationName."-ee":$installationName;
        $arguments = new ArrayInput($arguments);

        $this->getApplication()->find('place:files')->run($arguments, $output);
        $this->getApplication()->find('create:database')->run($arguments, $output);
        $this->getApplication()->find('install')->run($arguments, $output);

        return Command::SUCCESS;
    }
}
