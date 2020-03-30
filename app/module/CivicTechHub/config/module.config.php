<?php
return [
    'service_manager' => [
        'factories' => [
            \CivicTechHub\V1\Rest\Country\CountryResource::class => \CivicTechHub\V1\Rest\Country\CountryResourceFactory::class,
            \CivicTechHub\V1\Rest\Group\GroupResource::class => \CivicTechHub\V1\Rest\Group\GroupResourceFactory::class,
            \CivicTechHub\V1\Rest\ServiceLink\ServiceLinkResource::class => \CivicTechHub\V1\Rest\ServiceLink\ServiceLinkResourceFactory::class,
            \CivicTechHub\V1\Rest\Topic\TopicResource::class => \CivicTechHub\V1\Rest\Topic\TopicResourceFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'civic-tech-hub.rest.country' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/country[/:country_id]',
                    'defaults' => [
                        'controller' => 'CivicTechHub\\V1\\Rest\\Country\\Controller',
                    ],
                ],
            ],
            'civic-tech-hub.rest.group' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/group[/:group_id]',
                    'defaults' => [
                        'controller' => 'CivicTechHub\\V1\\Rest\\Group\\Controller',
                    ],
                ],
            ],
            'civic-tech-hub.rest.service-link' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/service-link[/:service_link_id]',
                    'defaults' => [
                        'controller' => 'CivicTechHub\\V1\\Rest\\ServiceLink\\Controller',
                    ],
                ],
            ],
            'civic-tech-hub.rest.topic' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/topic[/:topic_id]',
                    'defaults' => [
                        'controller' => 'CivicTechHub\\V1\\Rest\\Topic\\Controller',
                    ],
                ],
            ],
            'civic-tech-hub.rpc.search' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/search',
                    'defaults' => [
                        'controller' => 'CivicTechHub\\V1\\Rpc\\Search\\Controller',
                        'action' => 'search',
                    ],
                ],
            ],
            'civic-tech-hub.rpc.tmp-database-update' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/tmp-database-update',
                    'defaults' => [
                        'controller' => 'CivicTechHub\\V1\\Rpc\\TmpDatabaseUpdate\\Controller',
                        'action' => 'tmpDatabaseUpdate',
                    ],
                ],
            ],
        ],
    ],
    'api-tools-versioning' => [
        'uri' => [
            0 => 'civic-tech-hub.rest.country',
            1 => 'civic-tech-hub.rest.group',
            2 => 'civic-tech-hub.rest.service-link',
            3 => 'civic-tech-hub.rest.topic',
            4 => 'civic-tech-hub.rpc.search',
            5 => 'civic-tech-hub.rpc.tmp-database-update',
        ],
    ],
    'api-tools-rest' => [
        'CivicTechHub\\V1\\Rest\\Country\\Controller' => [
            'listener' => \CivicTechHub\V1\Rest\Country\CountryResource::class,
            'route_name' => 'civic-tech-hub.rest.country',
            'route_identifier_name' => 'country_id',
            'collection_name' => 'country',
            'entity_http_methods' => [
                0 => 'GET',
            ],
            'collection_http_methods' => [
                0 => 'GET',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \CivicTechHub\V1\Rest\Country\CountryEntity::class,
            'collection_class' => \CivicTechHub\V1\Rest\Country\CountryCollection::class,
            'service_name' => 'Country',
        ],
        'CivicTechHub\\V1\\Rest\\Group\\Controller' => [
            'listener' => \CivicTechHub\V1\Rest\Group\GroupResource::class,
            'route_name' => 'civic-tech-hub.rest.group',
            'route_identifier_name' => 'group_id',
            'collection_name' => 'group',
            'entity_http_methods' => [
                0 => 'GET',
            ],
            'collection_http_methods' => [
                0 => 'GET',
            ],
            'collection_query_whitelist' => [
                0 => 'countryId',
                1 => 'topicIds',
            ],
            'page_size' => 25,
            'page_size_param' => 'pageSize',
            'entity_class' => \CivicTechHub\V1\Rest\Group\GroupEntity::class,
            'collection_class' => \CivicTechHub\V1\Rest\Group\GroupCollection::class,
            'service_name' => 'Group',
        ],
        'CivicTechHub\\V1\\Rest\\ServiceLink\\Controller' => [
            'listener' => \CivicTechHub\V1\Rest\ServiceLink\ServiceLinkResource::class,
            'route_name' => 'civic-tech-hub.rest.service-link',
            'route_identifier_name' => 'service_link_id',
            'collection_name' => 'service_link',
            'entity_http_methods' => [
                0 => 'GET',
            ],
            'collection_http_methods' => [
                0 => 'GET',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \CivicTechHub\V1\Rest\ServiceLink\ServiceLinkEntity::class,
            'collection_class' => \CivicTechHub\V1\Rest\ServiceLink\ServiceLinkCollection::class,
            'service_name' => 'ServiceLink',
        ],
        'CivicTechHub\\V1\\Rest\\Topic\\Controller' => [
            'listener' => \CivicTechHub\V1\Rest\Topic\TopicResource::class,
            'route_name' => 'civic-tech-hub.rest.topic',
            'route_identifier_name' => 'topic_id',
            'collection_name' => 'topic',
            'entity_http_methods' => [
                0 => 'GET',
            ],
            'collection_http_methods' => [
                0 => 'GET',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \CivicTechHub\V1\Rest\Topic\TopicEntity::class,
            'collection_class' => \CivicTechHub\V1\Rest\Topic\TopicCollection::class,
            'service_name' => 'Topic',
        ],
    ],
    'api-tools-content-negotiation' => [
        'controllers' => [
            'CivicTechHub\\V1\\Rest\\Country\\Controller' => 'HalJson',
            'CivicTechHub\\V1\\Rest\\Group\\Controller' => 'HalJson',
            'CivicTechHub\\V1\\Rest\\ServiceLink\\Controller' => 'HalJson',
            'CivicTechHub\\V1\\Rest\\Topic\\Controller' => 'HalJson',
            'CivicTechHub\\V1\\Rpc\\Search\\Controller' => 'Json',
            'CivicTechHub\\V1\\Rpc\\TmpDatabaseUpdate\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'CivicTechHub\\V1\\Rest\\Country\\Controller' => [
                0 => 'application/vnd.civic-tech-hub.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'CivicTechHub\\V1\\Rest\\Group\\Controller' => [
                0 => 'application/vnd.civic-tech-hub.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'CivicTechHub\\V1\\Rest\\ServiceLink\\Controller' => [
                0 => 'application/vnd.civic-tech-hub.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'CivicTechHub\\V1\\Rest\\Topic\\Controller' => [
                0 => 'application/vnd.civic-tech-hub.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'CivicTechHub\\V1\\Rpc\\Search\\Controller' => [
                0 => 'application/vnd.civic-tech-hub.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'CivicTechHub\\V1\\Rpc\\TmpDatabaseUpdate\\Controller' => [
                0 => 'application/vnd.civic-tech-hub.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            'CivicTechHub\\V1\\Rest\\Country\\Controller' => [
                0 => 'application/vnd.civic-tech-hub.v1+json',
                1 => 'application/json',
            ],
            'CivicTechHub\\V1\\Rest\\Group\\Controller' => [
                0 => 'application/vnd.civic-tech-hub.v1+json',
                1 => 'application/json',
            ],
            'CivicTechHub\\V1\\Rest\\ServiceLink\\Controller' => [
                0 => 'application/vnd.civic-tech-hub.v1+json',
                1 => 'application/json',
            ],
            'CivicTechHub\\V1\\Rest\\Topic\\Controller' => [
                0 => 'application/vnd.civic-tech-hub.v1+json',
                1 => 'application/json',
            ],
            'CivicTechHub\\V1\\Rpc\\Search\\Controller' => [
                0 => 'application/vnd.civic-tech-hub.v1+json',
                1 => 'application/json',
            ],
            'CivicTechHub\\V1\\Rpc\\TmpDatabaseUpdate\\Controller' => [
                0 => 'application/vnd.civic-tech-hub.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'api-tools-hal' => [
        'metadata_map' => [
            \CivicTechHub\V1\Rest\Country\CountryEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'civic-tech-hub.rest.country',
                'route_identifier_name' => 'country_id',
                'hydrator' => \Laminas\Hydrator\ObjectPropertyHydrator::class,
            ],
            \CivicTechHub\V1\Rest\Country\CountryCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'civic-tech-hub.rest.country',
                'route_identifier_name' => 'country_id',
                'is_collection' => true,
            ],
            \CivicTechHub\V1\Rest\Group\GroupEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'civic-tech-hub.rest.group',
                'route_identifier_name' => 'group_id',
                'hydrator' => \Laminas\Hydrator\ObjectPropertyHydrator::class,
            ],
            \CivicTechHub\V1\Rest\Group\GroupCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'civic-tech-hub.rest.group',
                'route_identifier_name' => 'group_id',
                'is_collection' => true,
            ],
            \CivicTechHub\V1\Rest\ServiceLink\ServiceLinkEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'civic-tech-hub.rest.service-link',
                'route_identifier_name' => 'service_link_id',
                'hydrator' => \Laminas\Hydrator\ObjectPropertyHydrator::class,
            ],
            \CivicTechHub\V1\Rest\ServiceLink\ServiceLinkCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'civic-tech-hub.rest.service-link',
                'route_identifier_name' => 'service_link_id',
                'is_collection' => true,
            ],
            \CivicTechHub\V1\Rest\Topic\TopicEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'civic-tech-hub.rest.topic',
                'route_identifier_name' => 'topic_id',
                'hydrator' => \Laminas\Hydrator\ObjectPropertyHydrator::class,
            ],
            \CivicTechHub\V1\Rest\Topic\TopicCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'civic-tech-hub.rest.topic',
                'route_identifier_name' => 'topic_id',
                'is_collection' => true,
            ],
        ],
    ],
    'api-tools-content-validation' => [
        'CivicTechHub\\V1\\Rest\\Group\\Controller' => [
            'GET' => 'CivicTechHub\\V1\\Rest\\Group\\Validator\\GET',
        ],
        'CivicTechHub\\V1\\Rest\\ServiceLink\\Controller' => [
            'GET' => 'CivicTechHub\\V1\\Rest\\ServiceLink\\Validator\\GET',
        ],
        'CivicTechHub\\V1\\Rpc\\Search\\Controller' => [
            'GET' => 'CivicTechHub\\V1\\Rpc\\Search\\Validator\\GET',
        ],
        'CivicTechHub\\V1\\Rpc\\TmpDatabaseUpdate\\Controller' => [
            'GET' => 'CivicTechHub\\V1\\Rpc\\TmpDatabaseUpdate\\Validator\\GET',
        ],
    ],
    'input_filter_specs' => [
        'CivicTechHub\\V1\\Rest\\Group\\Validator\\GET' => [
            0 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Regex::class,
                        'options' => [
                            'pattern' => '/^[0-9]+$/',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                ],
                'name' => 'countryId',
            ],
            1 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Application\Validator\NonEmptyListOfUniqueIntegerIds::class,
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\PregReplace::class,
                        'options' => [
                            'pattern' => '/\\s/',
                            'replacement' => '',
                        ],
                    ],
                    1 => [
                        'name' => 'Application\\Filter\\CommaSeparatedListAsArray',
                        'options' => [],
                    ],
                ],
                'name' => 'topicIds',
            ],
        ],
        'CivicTechHub\\V1\\Rest\\ServiceLink\\Validator\\GET' => [
            0 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Regex::class,
                        'options' => [
                            'pattern' => '/^[1-9]{1}[0-9]*$/',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                ],
                'name' => 'groupId',
            ],
        ],
        'CivicTechHub\\V1\\Rpc\\Search\\Validator\\GET' => [
            0 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'min' => 2,
                        ],
                    ],
                ],
                'filters' => [],
                'name' => 'phrase',
                'field_type' => 'string',
            ],
            1 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \CivicTechHub\V1\Rpc\Search\Validator\ListWithUniqueSearchEntities::class,
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\PregReplace::class,
                        'options' => [
                            'pattern' => '/\\s/',
                            'replacement' => '',
                        ],
                    ],
                    1 => [
                        'name' => 'Application\\Filter\\CommaSeparatedListAsArray',
                        'options' => [],
                    ],
                ],
                'name' => 'entities',
                'field_type' => 'string',
            ],
            3 => [
                'required' => false,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Regex::class,
                        'options' => [
                            'pattern' => '/^[1-9]{1}[0-9]*$/',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                ],
                'name' => 'limit',
                'field_type' => 'string',
            ],
        ],
        'CivicTechHub\\V1\\Rpc\\TmpDatabaseUpdate\\Validator\\GET' => [
            0 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\NotEmpty::class,
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                ],
                'name' => 'token',
                'field_type' => 'string',
            ],
            1 => [
                'required' => false,
                'validators' => [
                ],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                ],
                'name' => 'migrate',
                'field_type' => 'string',
            ],
            2 => [
                'required' => false,
                'validators' => [
                ],
                'filters' => [
                    0 => [
                        'name' => \Zend\Filter\StringTrim::class,
                        'options' => [],
                    ],
                ],
                'name' => 'import',
                'field_type' => 'string',
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            'CivicTechHub\\V1\\Rpc\\Search\\Controller' => \CivicTechHub\V1\Rpc\Search\SearchControllerFactory::class,
            'CivicTechHub\\V1\\Rpc\\TmpDatabaseUpdate\\Controller' => \CivicTechHub\V1\Rpc\TmpDatabaseUpdate\TmpDatabaseUpdateControllerFactory::class,
        ],
    ],
    'api-tools-rpc' => [
        'CivicTechHub\\V1\\Rpc\\Search\\Controller' => [
            'service_name' => 'Search',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'civic-tech-hub.rpc.search',
        ],
        'CivicTechHub\\V1\\Rpc\\TmpDatabaseUpdate\\Controller' => [
            'service_name' => 'TmpDatabaseUpdate',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'civic-tech-hub.rpc.tmp-database-update',
        ],
    ],
];
