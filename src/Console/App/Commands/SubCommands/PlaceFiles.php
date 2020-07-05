<?php
namespace Console\App\Commands\SubCommands;

use Console\App\Commands\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PlaceFiles extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('place:files')
            ->addArgument('installation-name', InputArgument::REQUIRED, 'Installation Name.');
        $this->setHidden(true);
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $output->writeln(sprintf('<info>-> Downloading package</info>'));
            $version =  $input->getArgument('version');
            $config =  $this->config;
            $directory = $config['installation_root'].$input->getArgument("installation-name");
            if ($this->config['installation_method'] == "composer") {
                $command = " composer create-project --repository-url=https://repo.magento.com/ magento/project-{$input->getArgument('edition')}-edition=$version $directory";
                $this->runCommand($command);
            } else {
                $setupDir = $config['setup_directory'];
                $setupFile = $config['setup_directory']."/".$config['zip_file']['prefix'].$version.".".$config['zip_file']['type'];
                if (file_exists($setupFile)) {
                    $this->extractZip($setupFile, $directory,$output);
                } else {
                    $command = "cd ".$setupDir." && curl -O https://".$config['authentication']['mage_id'].":".$config['authentication']['token']."@www.magentocommerce.com/products/downloads/file/Magento-CE-$version.tar.gz";
                    $this->runCommand($command);
                    $output->writeln(sprintf('<comment>Package already exists</comment>'));
                    if (file_exists($setupFile)) {
                        $this->extractZip($setupFile, $directory, $output);
                    } else {
                        throw new \Exception("Unable to download magento setup version $command");
                    }
                }
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
        return Command::SUCCESS;
    }

    protected function extractZip($zip, $directory, $output)
    {
        $output->writeln(sprintf('<info>->Extracting filesystem</info>'));
        system("mkdir $directory");
        $this->runCommand("cd $directory && tar xf $zip");
    }
}
