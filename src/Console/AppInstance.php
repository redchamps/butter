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

    protected $root;

    public function __construct($config, $root)
    {
        $this->config = $config;
        $this->root = $root;
    }

    public function fetch()
    {
        $config = $this->config;
        $app = new Application();
        $app->add(new MagentoInstall($config, $this->root));
        $app->add(new ListInstallations($config, $this->root));
        $app->add(new DeleteInstallation($config, $this->root));
        $app->add(new PlaceFiles($config, $this->root));
        $app->add(new DeleteFiles($config, $this->root));
        $app->add(new CreateDatabase($config, $this->root));
        $app->add(new DeleteDatabase($config, $this->root));
        $app->add(new Install($config, $this->root));
        $app->add(new CopyConfig($config, $this->root));
        $app->add(new PreInstall($config, $this->root));
        $app->add(new PostInstall($config, $this->root));
        $app->add(new CreateVhost($config, $this->root));
        $app->add(new DeleteVhost($config, $this->root));
        $app->add(new RestartServer($config, $this->root));
        return $app;
    }
}
