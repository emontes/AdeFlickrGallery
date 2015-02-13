<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Foto\Controller\Index' => 'Foto\Controller\IndexController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'foto' => array(
                'type'    => 'Segment',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/foto[-:id]',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Foto\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Foto' => __DIR__ . '/../view',
        ),
    ),
);
