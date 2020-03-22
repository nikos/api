<?php
return [
    'service_manager' => [
        'factories' => [
            \Covid19CivicTech\V1\Rest\Country\CountryResource::class => \Covid19CivicTech\V1\Rest\Country\CountryResourceFactory::class,
            \Covid19CivicTech\V1\Rest\Group\GroupResource::class => \Covid19CivicTech\V1\Rest\Group\GroupResourceFactory::class,
            \Covid19CivicTech\V1\Rest\ServiceLink\ServiceLinkResource::class => \Covid19CivicTech\V1\Rest\ServiceLink\ServiceLinkResourceFactory::class,
            \Covid19CivicTech\V1\Rest\Topic\TopicResource::class => \Covid19CivicTech\V1\Rest\Topic\TopicResourceFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'covid19-civic-tech.rest.country' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/country[/:country_id]',
                    'defaults' => [
                        'controller' => 'Covid19CivicTech\\V1\\Rest\\Country\\Controller',
                    ],
                ],
            ],
            'covid19-civic-tech.rest.group' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/group[/:group_id]',
                    'defaults' => [
                        'controller' => 'Covid19CivicTech\\V1\\Rest\\Group\\Controller',
                    ],
                ],
            ],
            'covid19-civic-tech.rest.service-link' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/service-link[/:service_link_id]',
                    'defaults' => [
                        'controller' => 'Covid19CivicTech\\V1\\Rest\\ServiceLink\\Controller',
                    ],
                ],
            ],
            'covid19-civic-tech.rest.topic' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/topic[/:topic_id]',
                    'defaults' => [
                        'controller' => 'Covid19CivicTech\\V1\\Rest\\Topic\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'api-tools-versioning' => [
        'uri' => [
            0 => 'covid19-civic-tech.rest.country',
            1 => 'covid19-civic-tech.rest.group',
            2 => 'covid19-civic-tech.rest.service-link',
            3 => 'covid19-civic-tech.rest.topic',
        ],
    ],
    'api-tools-rest' => [
        'Covid19CivicTech\\V1\\Rest\\Country\\Controller' => [
            'listener' => \Covid19CivicTech\V1\Rest\Country\CountryResource::class,
            'route_name' => 'covid19-civic-tech.rest.country',
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
            'entity_class' => \Covid19CivicTech\V1\Rest\Country\CountryEntity::class,
            'collection_class' => \Covid19CivicTech\V1\Rest\Country\CountryCollection::class,
            'service_name' => 'Country',
        ],
        'Covid19CivicTech\\V1\\Rest\\Group\\Controller' => [
            'listener' => \Covid19CivicTech\V1\Rest\Group\GroupResource::class,
            'route_name' => 'covid19-civic-tech.rest.group',
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
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Covid19CivicTech\V1\Rest\Group\GroupEntity::class,
            'collection_class' => \Covid19CivicTech\V1\Rest\Group\GroupCollection::class,
            'service_name' => 'Group',
        ],
        'Covid19CivicTech\\V1\\Rest\\ServiceLink\\Controller' => [
            'listener' => \Covid19CivicTech\V1\Rest\ServiceLink\ServiceLinkResource::class,
            'route_name' => 'covid19-civic-tech.rest.service-link',
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
            'entity_class' => \Covid19CivicTech\V1\Rest\ServiceLink\ServiceLinkEntity::class,
            'collection_class' => \Covid19CivicTech\V1\Rest\ServiceLink\ServiceLinkCollection::class,
            'service_name' => 'ServiceLink',
        ],
        'Covid19CivicTech\\V1\\Rest\\Topic\\Controller' => [
            'listener' => \Covid19CivicTech\V1\Rest\Topic\TopicResource::class,
            'route_name' => 'covid19-civic-tech.rest.topic',
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
            'entity_class' => \Covid19CivicTech\V1\Rest\Topic\TopicEntity::class,
            'collection_class' => \Covid19CivicTech\V1\Rest\Topic\TopicCollection::class,
            'service_name' => 'Topic',
        ],
    ],
    'api-tools-content-negotiation' => [
        'controllers' => [
            'Covid19CivicTech\\V1\\Rest\\Country\\Controller' => 'HalJson',
            'Covid19CivicTech\\V1\\Rest\\Group\\Controller' => 'HalJson',
            'Covid19CivicTech\\V1\\Rest\\ServiceLink\\Controller' => 'HalJson',
            'Covid19CivicTech\\V1\\Rest\\Topic\\Controller' => 'HalJson',
        ],
        'accept_whitelist' => [
            'Covid19CivicTech\\V1\\Rest\\Country\\Controller' => [
                0 => 'application/vnd.covid19-civic-tech.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Covid19CivicTech\\V1\\Rest\\Group\\Controller' => [
                0 => 'application/vnd.covid19-civic-tech.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Covid19CivicTech\\V1\\Rest\\ServiceLink\\Controller' => [
                0 => 'application/vnd.covid19-civic-tech.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Covid19CivicTech\\V1\\Rest\\Topic\\Controller' => [
                0 => 'application/vnd.covid19-civic-tech.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'Covid19CivicTech\\V1\\Rest\\Country\\Controller' => [
                0 => 'application/vnd.covid19-civic-tech.v1+json',
                1 => 'application/json',
            ],
            'Covid19CivicTech\\V1\\Rest\\Group\\Controller' => [
                0 => 'application/vnd.covid19-civic-tech.v1+json',
                1 => 'application/json',
            ],
            'Covid19CivicTech\\V1\\Rest\\ServiceLink\\Controller' => [
                0 => 'application/vnd.covid19-civic-tech.v1+json',
                1 => 'application/json',
            ],
            'Covid19CivicTech\\V1\\Rest\\Topic\\Controller' => [
                0 => 'application/vnd.covid19-civic-tech.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'api-tools-hal' => [
        'metadata_map' => [
            \Covid19CivicTech\V1\Rest\Country\CountryEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'covid19-civic-tech.rest.country',
                'route_identifier_name' => 'country_id',
                'hydrator' => \Laminas\Hydrator\ObjectPropertyHydrator::class,
            ],
            \Covid19CivicTech\V1\Rest\Country\CountryCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'covid19-civic-tech.rest.country',
                'route_identifier_name' => 'country_id',
                'is_collection' => true,
            ],
            \Covid19CivicTech\V1\Rest\Group\GroupEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'covid19-civic-tech.rest.group',
                'route_identifier_name' => 'group_id',
                'hydrator' => \Laminas\Hydrator\ObjectPropertyHydrator::class,
            ],
            \Covid19CivicTech\V1\Rest\Group\GroupCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'covid19-civic-tech.rest.group',
                'route_identifier_name' => 'group_id',
                'is_collection' => true,
            ],
            \Covid19CivicTech\V1\Rest\ServiceLink\ServiceLinkEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'covid19-civic-tech.rest.service-link',
                'route_identifier_name' => 'service_link_id',
                'hydrator' => \Laminas\Hydrator\ObjectPropertyHydrator::class,
            ],
            \Covid19CivicTech\V1\Rest\ServiceLink\ServiceLinkCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'covid19-civic-tech.rest.service-link',
                'route_identifier_name' => 'service_link_id',
                'is_collection' => true,
            ],
            \Covid19CivicTech\V1\Rest\Topic\TopicEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'covid19-civic-tech.rest.topic',
                'route_identifier_name' => 'topic_id',
                'hydrator' => \Laminas\Hydrator\ObjectPropertyHydrator::class,
            ],
            \Covid19CivicTech\V1\Rest\Topic\TopicCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'covid19-civic-tech.rest.topic',
                'route_identifier_name' => 'topic_id',
                'is_collection' => true,
            ],
        ],
    ],
    'api-tools-content-validation' => [
        'Covid19CivicTech\\V1\\Rest\\Group\\Controller' => [
            'GET' => 'Covid19CivicTech\\V1\\Rest\\Group\\Validator\\GET',
        ],
        'Covid19CivicTech\\V1\\Rest\\ServiceLink\\Controller' => [
            'GET' => 'Covid19CivicTech\\V1\\Rest\\ServiceLink\\Validator\\GET',
        ],
    ],
    'input_filter_specs' => [
        'Covid19CivicTech\\V1\\Rest\\Group\\Validator\\GET' => [
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
        ],
        'Covid19CivicTech\\V1\\Rest\\ServiceLink\\Validator\\GET' => [
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
    ],
];
