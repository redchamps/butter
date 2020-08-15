<?php
namespace Console\App\Commands\SubCommands\Database;

use Console\App\Commands\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class DeleteDatabase extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('delete:database')
            ->addArgument('installation-name', InputArgument::REQUIRED, 'Installation Name.');
        $this->setHidden(true);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(
            sprintf('<info>-> Deleting Database</info>')
        );
        $this->validateAndDeleteDatabase($input, $output);
        return Self::SUCCESS;
    }

    protected function validateAndDeleteDatabase(InputInterface $input, OutputInterface $output)
    {
        try {
            $dbName = str_replace(
                "<version>",
                $input->getArgument("installation-name"),
                $this->config['db']['connection']['dbname']
            );
            $dsn = sprintf(
                'mysql:host=%s;port=%s',
                $this->config['db']['connection']['host'],
                $this->config['db']['connection']['port']
            );

            $db = new \PDO($dsn, $this->config['db']['connection']['username'], $this->config['db']['connection']['password']);

            if ($db->query('USE `' . $dbName . '`')) {
                $db->query('Drop DATABASE `' . $dbName . '`');
                $output->writeln('<info>->-> Database ' . $dbName . ' deleted</info>');
                return $db;
            } else {
                $output->writeln('<error>->-> Database ' . $dbName . ' doesn\'t exists</error>');
            }
            return $db;
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }

        return false;
    }
}
