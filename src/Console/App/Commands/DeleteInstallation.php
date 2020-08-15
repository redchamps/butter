<?php
namespace Console\App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class DeleteInstallation extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('magento:delete')
            ->setDescription('Delete Installed Magento 2 version')
            ->addArgument('installation-name', InputArgument::REQUIRED, 'Pass the installation name.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if(!is_array($this->config)) {
            $this->getApplication()->find('copy:config')->run(new ArrayInput([]), $output);
            return 1;
        }
        $installationName = $input->getArgument('installation-name');
        $output->writeln(
            sprintf('<info>Deleting Magento installation %s</info>', $installationName)
        );
        $this->getApplication()->find('delete:files')->run($input, $output);
        $this->getApplication()->find('delete:database')->run($input, $output);
        if($this->vhostEnabled()) {
            $arguments = $input->getArguments();
            $arguments['domain'] = str_replace(
                ["<version>", "http://",  "https://"],
                [$installationName, "", ""],
                rtrim($this->config['base_url'], "/")
            );
            $arguments['version'] = $installationName;
            unset($arguments['installation-name']);
            $arguments = new ArrayInput($arguments);
            $this->getApplication()->find('delete:vhost')->run($arguments, $output);
            $this->getApplication()->find('restart:server')->run($arguments, $output);
        }
        $output->writeln(
            sprintf('<info>Done :-)</info>')
        );
        return Self::SUCCESS;
    }
}
