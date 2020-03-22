<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-skeleton for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-skeleton/blob/master/LICENSE.md New BSD License
 */

namespace Application;

use Application\Console\ImportFromCsvCommand;
use Laminas\ModuleManager\Feature\ServiceProviderInterface;
use Laminas\ServiceManager\ServiceManager;

class Module implements ServiceProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories'=> [
                ImportFromCsvCommand::class => function (ServiceManager $serviceManager) {
                    $dbAdapter = $serviceManager->get('DbAdapterApi');

                    return new ImportFromCsvCommand($dbAdapter);
                }
            ]
        ];
    }
}
