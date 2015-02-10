<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Grupos\Controller\Index' => 'Grupos\Controller\IndexController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'grupos-index' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/grupos',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Grupos\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'grupo-paginator' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'      => '/:id[/pag][/:page]',
                            'constrains' => array(
                                'id'   => '[a-zA-Z0-9_-]+',
                                'page' => '[0-9*]'
                                
                            ),
                            'defaults'   => array(
                                'id'            => '1',
                                'page'          => '1',
                                'controller'    => 'Grupos\Controller\Index',
                                'action'        => 'index',
                            ),
                        ),
                    ),
                ),
            ), //grupos-index
            
            
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Grupos' => __DIR__ . '/../view',
        ),
    ),
    'module_layouts' => array(
        'Grupos' => 'layout/grupo.phtml',
    ),
    
);
