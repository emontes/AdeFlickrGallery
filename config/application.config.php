<?php
return array(
    'modules' => array(
        'Application',
        //'EdpLayouts',
        //'Home',
        //'Foto',
        //'Galerias',
        //'Grupos',
        'AdeFlickrGallery',
        
    ),

    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor','./module','./module','./module','./module',
        ),
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
    ),
    
    

    
);
