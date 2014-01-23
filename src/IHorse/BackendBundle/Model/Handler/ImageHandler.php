<?php
namespace IHorse\BackendBundle\Model\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\BrowserKit\Request;
use Guzzle\Http\Client;
use IHorse\BackendBundle\Util\StringHelper;

class ImageHandler extends RESTHandler
{
    public function getImages($params=null)
    {
        $get=StringHelper::getQueryArrayFromArray($params);
        $request = $this->client->get('images'.$get);
        $response = $request->send();
        $data = $response->json();
        
        return $data;
    }
    
    public function getImagesThumb($params=null)
    {
        $get=StringHelper::getQueryArrayFromArray($params);
        $request = $this->client->get('thumbnails'.$get);
        $response = $request->send();
        $data = $response->json();

        return $data;
    }

    public function getImage($id, $token)
    {
        return parent::get('images/'.$id, 'image', $token);
    }

    public function postImage($params, $files, $token)
    {
        $request = $this->client->post('images?access_token='.$token, null, $params)->addPostFiles($files);
        $response = $request->send();

        return $response->json();
    }

    public function putImage($id, $params)
    {
        return parent::put("images/".$id, $params);
    }

    public function deleteImage($id, $token)
    {
        return parent::delete("images/".$id, $token);
    }
}