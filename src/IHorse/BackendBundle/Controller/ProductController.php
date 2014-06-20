<?php

namespace IHorse\BackendBundle\Controller;

use IHorse\BackendBundle\Controller\IHorseController;
use IHorse\BackendBundle\Form\ClinicType;
use IHorse\BackendBundle\Form\ProductType;
use IHorse\BackendBundle\Form\ProdTranslationType;

class ProductController extends IHorseController
{
    public function listProductsAction()
    {
        $session = $this->getRequest()->getSession();
        $products = $this->get('rest.handler.model')->getList('products', 'products', $session->get('access_token'));

        return $this->render('BackendBundle:Product:index.html.twig', array('products' => $products));
    }

    public function newProductAction()
    {
        $form = $this->createForm(new ProductType());
        $trans1=$this->createForm(new ProdTranslationType());
        $trans2=$this->createForm(new ProdTranslationType());
        $trans3=$this->createForm(new ProdTranslationType());
        
        $form->get('translations')->setData(array($trans1, $trans2, $trans3));

        return $this->render('BackendBundle:Product:create.html.twig', array('form' => $form->createView()));
    }

    public function editProductAction($id)
    {
        $session = $this->getRequest()->getSession();
        $product = $this->get('rest.handler.model')->get('products/'.$id, 'product', $session->get('access_token'));
        $idioms = array('de_DE'=>0, 'es_ES'=>1, 'en_EN'=>2);

        foreach ($idioms as $key => $value) {
            if (isset($product['translations'][$value])) {
                if ($product['translations'][$value]['idiom']!=$key) {
                    $trans = array();
                    $product['translations'][$idioms[$product['translations'][$value]['idiom']]] = $product['translations'][$value];
                    $product['translations'][$value]=$trans;
                }
            } else {
                $trans = array();
                $product['translations'][]=$trans;
            }
        }
        $form = $form = $this->createForm(new ProductType(), $product);

        return $this->render('BackendBundle:Product:create.html.twig', array('form' => $form->createView(),'edition' => true, 'id' => $id));
    }

    public function createProductAction()
    {
        $session = $this->getRequest()->getSession();
        $params = $this->getRequest()->request->get('product');
        $clinic = $this->get('rest.handler.model')->post("products", array('product' => $params), $session->get('access_token'));

        return $this->redirect($this->generateUrl('products_list'));
    }

    public function putProductAction($id)
    {
        $session = $this->getRequest()->getSession();
        $params = $this->getRequest()->request->get('product');
        $clinic = $this->get('rest.handler.model')->put("products/".$id, array('product' => $params), $session->get('access_token'));

        return $this->redirect($this->generateUrl('products_list'));
    }

    public function deleteProductAction($id)
    {
        $session = $this->getRequest()->getSession();
        $clinic = $this->get('rest.handler.model')->delete("products/".$id, $session->get('access_token'));

        return $this->redirect($this->generateUrl('products_list'));
    }
}