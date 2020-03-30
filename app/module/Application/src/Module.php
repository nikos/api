<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-skeleton for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-skeleton/blob/master/LICENSE.md New BSD License
 */

namespace Application;

use Laminas\Filter\Callback;
use Laminas\ModuleManager\Feature\FilterProviderInterface;
use Laminas\ModuleManager\Feature\ServiceProviderInterface;

class Module implements ServiceProviderInterface, FilterProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories'=> []
        ];
    }

    public function getFilterConfig()
    {
        return [
            'factories' => [
                'Application\Filter\CommaSeparatedListAsArray' => function () {
                    return new Callback([
                        'callback' => function ($commaSeparatedValues) {
                            if (trim($commaSeparatedValues) == '') {
                                return [];
                            }

                            return explode(',', $commaSeparatedValues);
                        }
                    ]);
                },
            ],
        ];
    }
}
