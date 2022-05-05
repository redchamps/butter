<?php
namespace Console\App\Commands\SubCommands\Database;

use Console\App\Commands\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CreateDatabase extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('create:database')
            ->addArgument('installation-name', InputArgument::REQUIRED, 'Installation Name.');
        $this->setHidden(true);
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<info>-> Creating Database</info>'));
        $this->validateAndCreateDatabase($input, $output);
        return Self::SUCCESS;
    }

    protected function validateAndCreateDatabase(InputInterface $input, OutputInterface $output)
    {
        try {
            $dbName = str_replace("<version>", $input->getArgument("installation-name"), $this->config['db']['connection']['dbname']);
            $_SERVER['db_name'] = $dbName;
            $dsn = sprintf(
                'mysql:host=%s;port=%s',
                $this->config['db']['connection']['host'],
                $this->config['db']['connection']['port']
            );

            $db = new \PDO($dsn, $this->config['db']['connection']['username'], $this->config['db']['connection']['password']);

            //if (!$db->query('USE `' . $dbName . '`')) {
                $db->query('CREATE DATABASE `' . $dbName . '`');
                $output->writeln('<info>->-> Created database ' . $dbName . '</info>');
                $db->query('USE `' . $dbName . '`');

                // Check DB version
                $statement = $db->query('SELECT VERSION()');
                $mysqlVersion = $statement->fetchColumn(0);
                if (version_compare($mysqlVersion, '5.6.0', '<')) {
                    throw new \Exception('MySQL Version must be >= 5.6.0');
                }

                return $db;
            //}

            //return $db;
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }

        return false;
    }
}
