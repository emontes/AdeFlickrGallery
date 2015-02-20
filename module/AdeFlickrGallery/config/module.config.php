<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'AdeFlickrGallery\Controller\Home'        => 'AdeFlickrGallery\Controller\HomeController',
            'AdeFlickrGallery\Controller\Foto'        => 'AdeFlickrGallery\Controller\FotoController',
            'AdeFlickrGallery\Controller\Grupos'      => 'AdeFlickrGallery\Controller\GruposController',
            'AdeFlickrGallery\Controller\Colecciones' => 'AdeFlickrGallery\Controller\ColeccionesController',
            'AdeFlickrGallery\Controller\Album'       => 'AdeFlickrGallery\Controller\AlbumController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'AdeFlickrGallery\Controller',
                        'controller'    => 'Home',
                        'action'        => 'index',
                    ),
                ),
            ),
            'album' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/album[/:id]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'AdeFlickrGallery\Controller',
                        'controller'    => 'Album',
                        'action'        => 'index',
                    ),
                ),
            ),//album
            'coleccion' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/coleccion[/:id]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'AdeFlickrGallery\Controller',
                        'controller'    => 'Colecciones',
                        'action'        => 'index',
                    ),
                ),
            ),//coleccion
            'foto' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/foto[-:id][/grupo-:grupo][/pag/:page]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'AdeFlickrGallery\Controller',
                        'controller'    => 'Foto',
                        'action'        => 'index',
                    ),
                ),
            
            ),//foto
            'grupos-index' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/grupos',
                    'defaults' => array(
                        '__NAMESPACE__' => 'AdeFlickrGallery\Controller',
                        'controller'    => 'Grupos',
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
                                'controller'    => 'AdeFlickrGallery\Controller\Grupos',
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
            'grupos-navigation' => 'AdeFlickrGallery\Factory\GruposNavigationFactory',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'AdeFlickrGallery' => __DIR__ . '/../view',
        ),
    ),
    'navigation' => array(
        'default' => array(
//             array(
//                 'label' => 'Home',
//                 'route' => 'home',
//                 'order' => '100',
//             ),
            array(
                'label' => 'Grupos',
                'route' => 'grupos-index',
                'order' => '300',
            ),
            array(
                'id'    => 'external_sites',
                'label' => 'Otras Galerías',
                'uri'   => '#',
                'order' => '700',
            )
        ),
    ),
);