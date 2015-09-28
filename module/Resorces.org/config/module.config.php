<?php


return array(
    'router' => array(
        'routes' => array(
            'cover' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/Cover/Show',
                    'defaults' => array(
                        'controller' => 'cover',
                        'action'     => 'show'
                    )
                )
            ),
            'cover-unavailable' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/Cover/Unavailable',
                    'defaults' => array(
                        'controller' => 'cover',
                        'action'     => 'unavailable'
                    ),
                ),
            ),

        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'cover'             => 'Resources\Controller\CoverController',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Cover\Http' => 'Resources\Http\HttpFactory',
            'resourcesConfig'   => 'Resources\Config\ResourcesConfig'
        )

    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'layout/header'           => __DIR__ . '/../view/layout/header.phtml',
            'layout/footer'           => __DIR__ . '/../view/layout/footer.phtml',
            'layout/sidebar'          => __DIR__ . '/../view/layout/sidebar.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),


);
