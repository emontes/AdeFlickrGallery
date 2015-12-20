<?php
/**
 * AdeFlickrGallery
 *
 * @link      https://github.com/emontes/AdeFlickrGallery
 */

namespace AdeFlickrGallery\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use AdeFlickrGallery\Service\FotoService;
use AdeFlickrGallery\Service\FlickrPhoto;
use AdeFlickrGallery\Service\AlbumService;

class FotoController extends AbstractActionController
{
    public function indexAction()
    {
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            $id = '5964609109';
        }
        $grupoId = $this->params()->fromRoute('grupo');
        $albumId = $this->params()->fromRoute('album');
        $page = $this->params()->fromRoute('page');
        
        $config       = $this->getServiceLocator()->get('config');
        $configFlickr = $config['flickr'];
        
        $flickr = new FlickrPhoto($configFlickr['key']);
        $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
        
        $retval = array();
        if (isset($grupoId)) {
            $grupo = array(
              'link'  => '/grupos/' . $grupoId,
              'title' => $configFlickr['groups'][$grupoId]['title'],  
            );
            if (isset($page)) {
                $grupo['link'] .= '/pag/' . $page;
            }
            $retval['grupo'] = $grupo;
        }
        
        if (isset($albumId)) {
            $albumService = new AlbumService();
            $albumInfo = $albumService->getAlbumInfo($flickr, $albumId);
            
            $album = array(
                'link'   => '/album/' . $albumId,
                'title'  => $albumInfo['title'],
            );
            if (isset($page)) {
                $album['link'] .= '/pag/' .$page;
            }
            $retval['album']       = $album;
            
        }
        
        
        $config = $this->getServiceLocator()->get('config');
        $configFlickr = $config['flickr'];
        
        $fotoService = new FotoService();
        
        
        $idarray = explode('+', $id);
        $notAllowedPictures = $configFlickr['notAllowedPictures'];
        foreach ($notAllowedPictures as $naPicture) {
            if (in_array($naPicture, $idarray)) {
                $this->flashmessenger()->addErrorMessage('No estamos autorizados para desplegar la imagen' . $naPicture);
                $this->redirect()->toRoute('home');
            }
        }
        
        $photos = array();
        foreach ($idarray as $id) {
            $photos[] = array(
                'id'      => $id,
                'details' => $fotoService->getPhotoDetails($flickr, $id),
                'info'    => $fotoService->getPhotoInfo($flickr, $id),
                'exif'    => $fotoService->getPhotoExif($flickr, $id),
            );
        }
        
        $retval['photos'] = $photos;
        $retval['allowedTags'] = $configFlickr['allowedTags'];
        return $retval;
    }

}
