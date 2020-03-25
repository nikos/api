<?php
namespace CivicTechHub;

use CivicTechHub\V1\Rest\Country;
use CivicTechHub\V1\Rest\Group;
use CivicTechHub\V1\Rest\Topic;
use CivicTechHub\V1\Rest\ServiceLink;
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

                    $hydrator = $this->getDefaultDatabaseHydrator();
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
                },
                Group\Model\RepositoryInterface::class => function (ServiceManager $serviceManager) {
                    $dbAdapter = $serviceManager->get('DbAdapterApi');

                    $resultSetPrototype = new HydratingResultSet();
                    $resultSetPrototype->setHydrator($this->getDefaultDatabaseHydrator());
                    $resultSetPrototype->setObjectPrototype(new Group\GroupEntity());

                    $tableGateway = new TableGateway('group', $dbAdapter, null, $resultSetPrototype);

                    return new Group\Model\DatabaseRepository(
                        $tableGateway,
                        $serviceManager->get(Country\Model\RepositoryInterface::class),
                        $serviceManager->get(Topic\Model\RepositoryInterface::class),
                        $serviceManager->get(ServiceLink\Model\RepositoryInterface::class)
                    );
                },
                Topic\Model\RepositoryInterface::class => function (ServiceManager $serviceManager) {
                    $dbAdapter = $serviceManager->get('DbAdapterApi');

                    $resultSetPrototype = new HydratingResultSet();
                    $resultSetPrototype->setHydrator($this->getDefaultDatabaseHydrator());
                    $resultSetPrototype->setObjectPrototype(new Topic\TopicEntity());

                    $tableGateway = new TableGateway('topic', $dbAdapter, null, $resultSetPrototype);

                    return new Topic\Model\DatabaseRepository($tableGateway);
                },
                ServiceLink\Model\RepositoryInterface::class => function (ServiceManager $serviceManager) {
                    $dbAdapter = $serviceManager->get('DbAdapterApi');

                    $resultSetPrototype = new HydratingResultSet();
                    $resultSetPrototype->setHydrator($this->getDefaultDatabaseHydrator());
                    $resultSetPrototype->setObjectPrototype(new ServiceLink\ServiceLinkEntity());

                    $tableGateway = new TableGateway('service_link', $dbAdapter, null, $resultSetPrototype);

                    return new ServiceLink\Model\DatabaseRepository($tableGateway);
                }
            ]
        ];
    }

    private function getDefaultDatabaseHydrator()
    {
        $hydrator = new ObjectPropertyHydrator();
        $hydrator->setNamingStrategy(new UnderscoreNamingStrategy());

        return $hydrator;
    }
}
