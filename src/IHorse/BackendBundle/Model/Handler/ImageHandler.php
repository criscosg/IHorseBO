<?php
namespace IHorse\BackendBundle\Model\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\BrowserKit\Request;
use Guzzle\Http\Client;

class ImageHandler extends RESTHandler
{
    public function getImages($params=null)
    {
        $get="";
        if ($params) {
            $get="?";
            foreach ($params as $key=>$value) {
                reset($params);
                if ($key === key($params)) {
                    $get.=$key."=".$value;
                } else {
                    $get.="&".$key."=".$value;
                }
            }
        }
        
        return parent::getList('images'.$get, 'images');
    }
    
    public function getImagesThumb($params=null)
    {
        $get="";
        if ($params) {
            $get="?";
            foreach ($params as $key=>$value) {
                reset($params);
                if ($key === key($params)) {
                    $get.=$key."=".$value;
                } else {
                    $get.="&".$key."=".$value;
                }
            }
        }

        return parent::getList('thumbnails'.$get);
    }

    public function getImage($id)
    {
        return parent::get('images/'.$id, 'image');
    }

    public function postImage($params, $files)
    {
        $request = $this->client->post('images', null, $params)->addPostFiles($files);
        $response = $request->send();
        
        return $response->json();
        
        return parent::post("images", $params);
    }

    public function putImage($id, $params)
    {
        return parent::put("images/".$id, $params);
    }

    public function deleteImage($id)
    {
        return parent::delete("images/".$id);
    }
}