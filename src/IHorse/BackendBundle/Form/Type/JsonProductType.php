<?php
namespace IHorse\BackendBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use IHorse\BackendBundle\Model\Handler\RESTHandler;

class JsonProductType extends AbstractType
{
    private $handler;
    private $container;

    public function __construct(RESTHandler $handler, ContainerInterface $container)
    {
        $this->handler = $handler;
        $this->container = $container;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'choices' => $this->getChoices(),
        ));
    }

    private function getChoices()
    {
        $products = $this->handler->getList('products', 'product', $this->container->get('session')->get('access_token'));
        $final = array();
        $id=1;
        foreach($products as $product){
            foreach ($product as $key => $value) {
                if ($key == 'id'){
                    $id = $value;
                }
                if ($key == 'title'){
                    $final[$id] = $value;
                }
            }
        }

        return $final;
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'json_product';
    }

}