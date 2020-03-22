<?php
namespace Covid19CivicTech;

use Covid19CivicTech\V1\Rest\Country;
use Laminas\ApiTools\Provider\ApiToolsProviderInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Hydrator\NamingStrategy\CompositeNamingStrategy;
use Laminas\Hydrator\NamingStrategy\MapNamingStrategy;
use Laminas\Hydrator\NamingStrategy\UnderscoreNamingStrategy;
use Laminas\Hydrator\ObjectPropertyHydrator;
use Laminas\ModuleManager\Feature\ServiceProviderInterface;
use Laminas\ServiceManager\ServiceManager;

class Module implements ApiToolsProviderInterface, ServiceProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return [
            'Laminas\ApiTools\Autoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src',
                ],
            ],
        ];
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Country\Model\RepositoryInterface::class => function (ServiceManager $serviceManager) {
                    $dbAdapter = $serviceManager->get('DbAdapterApi');

                    $hydrator = new ObjectPropertyHydrator();
                    $mapNamingStrategy = MapNamingStrategy::createFromExtractionMap(['iso3166Code' => 'iso_3166_code']);
                    $hydrator->setNamingStrategy(new CompositeNamingStrategy(
                        [
                            'iso_3166_code' => $mapNamingStrategy,
                            'iso3166Code' => $mapNamingStrategy,
                        ],
                        new UnderscoreNamingStrategy()
                    ));

                    $resultSetPrototype = new HydratingResultSet();
                    $resultSetPrototype->setHydrator($hydrator);
                    $resultSetPrototype->setObjectPrototype(new Country\CountryEntity());

                    $tableGateway = new TableGateway('country', $dbAdapter, null, $resultSetPrototype);

                    return new Country\Model\DatabaseRepository($tableGateway);
                }
            ]
        ];
    }
}
