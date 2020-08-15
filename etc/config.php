<?php
/*
 * Don't change values like
 * <version>
 * <installation-path>
 * They're variables & will be replaced with dynamic values at runtime
 * */
return [
    'db' => [
        'table_prefix' => '',
        'connection' => [
            'host' => 'localhost',
            'dbname' => 'magento_<version>',
            'username' => '',
            'password' => '',
            'port' => 3306
        ]
    ],
    /*
     * #Required
     * This required to download zip packages from Magento server, if you don't want to input these details
     * then change 'installation_method' setting to 'composer' instead of 'zip'
     * Login to your account on magento.com and generate it in Account Settings->Downloads Access Token
     * MageId can be found there as well
     * */
    'authentication' => [
        'token' => '',
        'mage_id' => ''
    ],
    'installation_root' => '/var/www/html/',
    'setup_directory' => '~',
    'base_url' => 'http://<version>.test',
    'zip_file' => [
        'ce_prefix' => 'Magento-CE-',
        'type'  => 'tar.gz'
    ],
    'installation_method' => "zip", //Allowed values zip, composer
    /*
     * Specify if performance profile needs to be generated for every installation
     * */
    'generate_performance_profile' => 'n', //allowed values n, small, medium, large, extra_large
    'installation_options' => [
        "frontname" => "admin",
        "admin-username" => "admin",
        "admin-password" => "admin123",
        "admin-firstname" => "Admin",
        "admin-lastname" => "User",
        "admin-email" => "admin@example.com"
    ],
    /*
     * Specify  composer package names of extra extensions if needs to be installed
     * For, example 'redchamps/module-easy-cache-clean' for
     * http://github.com/redchamps/easy-cache-clean
     * */
    'extra_extensions' => [

    ],
    /*
     * Enter additional commands that needs to ran after Magento installation
     * */
    'post_install_commands' => [

    ],
    /*
     * Config area for automatic nginx vhost generation
     * Set enabled to true & adjust other settings as per your environment
     * */
    'vhost' => [
        'enabled' => false, //allowed value true, false
        'nginx_path' => '/etc/nginx/',
        'server_restart_command' => 'service nginx restart',
        'vhost_config' => 'server {
              listen 80;
              server_name <domain>;
              set $MAGE_ROOT <installation-path>;
              include <installation-path>/nginx.conf.sample;
            }'
    ]
];
