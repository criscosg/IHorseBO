<?php
namespace IHorse\BackendBundle\Controller;

use IHorse\BackendBundle\Controller\IHorseController;
use IHorse\BackendBundle\Form\HorseType;
use IHorse\BackendBundle\Form\ImageType;

class ImageController extends IHorseController
{
    public function newImageAction($horseId)
    {
        $form = $form = $this->createForm(new ImageType());

        return $this->render('BackendBundle:Image:create.html.twig', array('form' => $form->createView(), 'horse'=>$horseId));
    }

    public function createImageAction()
    {
        $session = $this->getRequest()->getSession();
        $file=$this->getRequest()->files->get('image');
        $files['image[file]']="@".$file['file']->getPathname();
        $params = $this->getRequest()->request->all();
        $image = $this->get('image.handler.model')->postImage($params, $files, $session->get('access_token'));

        return $this->redirect($this->generateUrl('show_horse', array('id'=>$params['image']['horse'])));
    }

    public function deleteImageAction($id)
    {
        $session = $this->getRequest()->getSession();
        $image = $this->get('image.handler.model')->deleteImage($id, $session->get('access_token'));

        return $this->redirect($this->generateUrl('show_horse', array('id'=>$params['horse'])));
    }
}