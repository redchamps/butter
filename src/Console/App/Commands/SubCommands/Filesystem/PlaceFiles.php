<?php
namespace Console\App\Commands\SubCommands\Filesystem;

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
            if ($input->getOption('method') == "composer" || $input->getOption('edition') == "enterprise") {
                $command = " composer create-project --repository-url=https://repo.magento.com/ magento/project-{$input->getOption('edition')}-edition=$version $directory";
                system($command);
            } else {
                $setupDir = $config['setup_directory'];
                if(!file_exists($setupDir)) {
                    $output->writeln(sprintf('<comment>Setup directory not exists .. creating</comment>'));
                    system("mkdir $setupDir");
                    $output->writeln(sprintf('<comment>Done .. Created.</comment>'));
                }
                $prefix = $config['zip_file']['ce_prefix'];
                $setupFile = $config['setup_directory']."/".$prefix.$version.".".$config['zip_file']['type'];
                if (file_exists($setupFile)) {
                    $this->extractZip($setupFile, $directory,$output);
                } else {
                    $command = "cd ".$setupDir." && curl -O https://".$config['authentication']['mage_id'].":".$config['authentication']['token']."@www.magentocommerce.com/products/downloads/file/$prefix$version.tar.gz";
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
        return Self::SUCCESS;
    }

    protected function extractZip($zip, $directory, $output)
    {
        $output->writeln(sprintf('<info>-> Extracting filesystem</info>'));
        if(!file_exists($directory)) {
            system("mkdir $directory");
        }
        $this->runCommand("cd $directory && tar xf $zip");
    }
}
