<?php
return ['db' => [
    'table_prefix' => '',
    'connection' => [
        'host' => 'localhost',
        'dbname' => 'magento_<version>',
        'username' => 'root',
        'password' => 'root',
        'port' => 3306
    ]
],
    'authentication' => [
        'token' => '9950ab04e82f33fa57e4b5ae7eacd8ac342f0d42',
        'mage_id' => 'MAG005200257'
    ],
    'installation_root' => '/Users/rav/Sites/test-installations/m2/',
    'setup_directory' => '/Users/rav/Sites/project/magento-installer/setups',
    'base_url' => 'http://<version>.test',
    'zip_file' => [
        'prefix' => 'Magento-CE-',
        'type'  => 'tar.gz'
    ],
    'installation_method' => "zip"
];
