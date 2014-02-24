<?php
namespace IHorse\BackendBundle\Controller;

use IHorse\BackendBundle\Controller\IHorseController;
use IHorse\BackendBundle\Form\HorseType;
use IHorse\BackendBundle\Form\ImageType;

class ImageController extends IHorseController
{
    public function newImageAction($horseId, $dentalId)
    {
        $session = $this->getRequest()->getSession();
        $dental = $this->get('dental.handler.model')->getDental($horseId, $dentalId, $session->get('access_token'));
        $form = $form = $this->createForm(new ImageType());

        return $this->render('BackendBundle:Image:create.html.twig', array('form' => $form->createView(), 'horse'=>$horseId, 'dental'=>$dental));
    }

    public function createImageAction($horseId, $dentalId)
    {
        $session = $this->getRequest()->getSession();
        $file=$this->getRequest()->files->get('image');
        $files['image[file]']="@".$file['file']->getPathname();
        $params = $this->getRequest()->request->all();
        $image = $this->get('image.handler.model')->postImage($params, $files, $session->get('access_token'));

        return $this->redirect($this->generateUrl('show_dental', array('horse'=>$horseId, 'id'=>$dentalId)));
    }

    public function deleteImageAction($id)
    {
        $session = $this->getRequest()->getSession();
        $image = $this->get('image.handler.model')->deleteImage($id, $session->get('access_token'));

        return $this->redirect($this->generateUrl('show_horse', array('id'=>$params['horse'])));
    }
}