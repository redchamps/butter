# Butter - Smooth Magento 2 Versions Installer 

[![Latest Stable Version](https://img.shields.io/packagist/v/redchamps/butter.svg?style=flat-square)](https://packagist.org/packages/redchamps/butter)  [![Packagist](https://img.shields.io/packagist/dt/redchamps/butter.svg?style=flat-square)](https://packagist.org/packages/redchamps/butter/stats) [![Packagist](https://img.shields.io/packagist/dm/redchamps/butter.svg?style=flat-square)](https://packagist.org/packages/redchamps/butter/stats)

## Intoduction

Butter will allow you to install a Magento version with just a single command. No, need to manually downloading, placing files and creating database.

For example, a command `butter magento:install 2.4.0` will

1. Automatically download the Magento version 2.4.0 package
2. Automatically place it in configured path
3. Create database for it, obviously automatically
4. Execute the installation command
5. Create virtual host(if enabled)
6. Returns frontend & backend URLs 

Sounds interesting? It has still more to offer. This can list all the installed version using command

`butter magento:list`

Additionally, you can delete any installtion with just a single command

`butter magento:delete <installation-name>`

For example, command  `butter magento:delete 240` for deleting 2.4.0 installation

## Installation and Configuration

Go here for the [Butter documentation](https://github.com/redchamps/butter/wiki)

## How it differs from n98magerun

You may know that Magento version can be installed using n98magerun as well. So, why we developed **Butter**?

**Reasons are:**

1. It can create automatic virtual hosts as well so that you have a ready to use installation with just a single command
2. You do not need to specify all the parameters like database name, admin user details etc to installation command. The installation command is simple & sweet :-)
3. Along, with composer, **Butter** primarily use ZIP based Magento version packages as defined here https://gist.github.com/piotrekkaminski/9bc45ec84028611d621e. Advantage of this technique is, the re-installation of same version will we very quick
as the package is already downloaded & stored

The baseline is n98magerun is built to do lot of things & butter is a specialised tool for doing installations.  

## Authors

- RedChamps [Maintainer] [![Twitter Follow](https://img.shields.io/twitter/follow/_redChamps.svg?style=social)](https://twitter.com/_redChamps)
- Ravinder [Maintainer] [![Twitter Follow](https://img.shields.io/twitter/follow/_iAmRav.svg?style=social)](https://twitter.com/_iAmRav)


## License

This project is licensed under the Open Source License 

## ADS

Please visit our [store](https://redchamps.com?utm_source=github_butter) for more free/paid extensions from us.
