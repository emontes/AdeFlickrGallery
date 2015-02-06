<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Home\Controller\Home' => 'Home\Controller\HomeController',
            'Home\Controller\Photo' => 'Home\Controller\PhotoController'
        ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Home\Controller',
                        'controller'    => 'Home',
                        'action'        => 'index',
                    ),
                ),
            ),
            'photo' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/photo[-:id]',
                    'constraints' => array(
                        //'id'     => '[0-9+]'
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Home\Controller',
                        'controller'    => 'Photo',
                        'action'        => 'index',
                    ),
                ),
            ),
            
            
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'photo-service' => 'Home\Service\PhotoService',
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Home' => __DIR__ . '/../view',
        ),
    ),
);
