<?php
namespace Console;

use Symfony\Component\Console\Application;
use Console\App\Commands\MagentoInstall;
use Console\App\Commands\ListInstallations;
use Console\App\Commands\DeleteInstallation;
use Console\App\Commands\SubCommands\Filesystem\PlaceFiles;
use Console\App\Commands\SubCommands\Filesystem\DeleteFiles;
use Console\App\Commands\SubCommands\Database\CreateDatabase;
use Console\App\Commands\SubCommands\Database\DeleteDatabase;
use Console\App\Commands\SubCommands\Installation\Install;
use Console\App\Commands\SubCommands\CopyConfig;
use Console\App\Commands\SubCommands\Installation\PreInstall;
use Console\App\Commands\SubCommands\Installation\PostInstall;
use Console\App\Commands\SubCommands\Vhost\CreateVhost;
use Console\App\Commands\SubCommands\Vhost\DeleteVhost;
use Console\App\Commands\SubCommands\RestartServer;

class AppInstance
{
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }
    public function fetch()
    {
        $config = $this->config;
        $app = new Application();
        $app->add(new MagentoInstall($config));
        $app->add(new ListInstallations($config));
        $app->add(new DeleteInstallation($config));
        $app->add(new PlaceFiles($config));
        $app->add(new DeleteFiles($config));
        $app->add(new CreateDatabase($config));
        $app->add(new DeleteDatabase($config));
        $app->add(new Install($config));
        $app->add(new CopyConfig($config));
        $app->add(new PreInstall($config));
        $app->add(new PostInstall($config));
        $app->add(new CreateVhost($config));
        $app->add(new DeleteVhost($config));
        $app->add(new RestartServer($config));
        return $app;
    }
}
