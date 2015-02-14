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
        'factories'  => array(
            'grupos-navigation' => 'Grupos\Factory\GruposNavigationFactory',
        ),
        'invokables' => array(
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Grupos' => __DIR__ . '/../view',
        ),
    ),
    
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Grupos',
                'route' => 'grupos-index',
                
            ),
            array(
                'label' => 'Otras Galerías',
                'uri'   => '#',
                'pages' => array(
                    array(
                        'label' => 'Turista',
                        'uri'   => 'http://www.turista.com.mx',
                        'pages' => array(
                            array(
                                'label' => 'Chiapas',
                                'uri'   => 'http://www.chiapaspictures.com'
                            ),
                            array(
                                'label' => 'México',
                                'uri'   => 'http://www.mexicopictures.net'
                            ),
                            array(
                                'label' => 'Cancún',
                                'uri'   => 'http://www.cancunpictures.net'
                            ),
                            array(
                                'label' => 'Cancún Image Gallery',
                                'uri'   => 'http://turistacancun.com/gallery'
                            ),
                            array(
                                'label' => 'Yucatan',
                                'uri'   => 'http://www.yucatanpictures.com'
                            ),
                        )
                    ),
                    array(
                        'label' => 'Social',
                        'uri'   => '#',
                        'pages' => array(
                            array(
                                'label' => 'Flickr Turista',
                                'uri'   => 'https://www.flickr.com/photos/turistamexico/',
                            )
                        )
                    )
                    
                )
            )
            
        ),
    ),
    
    
);
