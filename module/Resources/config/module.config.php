<?php


return [
    'router' => [
        'routes' => [
            'cover' => [
                'type'    => 'literal',
                'options' => [
                    'route'    => '/Cover/Show',
                    'defaults' => [
                        'controller' => 'cover',
                        'action'     => 'show'
                    ]
                ]
            ],
            'cover-unavailable' => [
                'type'    => 'literal',
                'options' => [
                    'route'    => '/Cover/Unavailable',
                    'defaults' => [
                        'controller' => 'cover',
                        'action'     => 'unavailable'
                    ],
                ],
            ],

        ],
    ],
    'controllers' => [
        'invokables' => [
            'cover'             => 'Resources\Controller\CoverController',
        ],
    ],
    'service_manager' => [
        'factories' => [
            'Cover\Http' => 'Resources\Http\HttpFactory',
            'resourcesConfig'   => 'Resources\Config\ResourcesConfig',
            'Resources\ContentCoversPluginManager' => 'Resources\Service\Factory::getContentCoversPluginManager',

         ],

    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'layout/header'           => __DIR__ . '/../view/layout/header.phtml',
            'layout/footer'           => __DIR__ . '/../view/layout/footer.phtml',
            'layout/sidebar'          => __DIR__ . '/../view/layout/sidebar.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'vufind' => [
        // The config reader is a special service manager for loading .ini files:
        'config_reader' => [
            'abstract_factories' => ['VuFind\Config\PluginFactory'],
        ],
        // PostgreSQL sequence mapping
        'pgsql_seq_mapping'  => [
            'comments'       => ['id', 'comments_id_seq'],
            'oai_resumption' => ['id', 'oai_resumption_id_seq'],
            'resource'       => ['id', 'resource_id_seq'],
            'resource_tags'  => ['id', 'resource_tags_id_seq'],
            'search'         => ['id', 'search_id_seq'],
            'session'        => ['id', 'session_id_seq'],
            'tags'           => ['id', 'tags_id_seq'],
            'user'           => ['id', 'user_id_seq'],
            'user_list'      => ['id', 'user_list_id_seq'],
            'user_resource'  => ['id', 'user_resource_id_seq']
        ],
        // This section contains service manager configurations for all VuFind
        // pluggable components:
        'plugin_managers' => [
        ]
        ]




];
