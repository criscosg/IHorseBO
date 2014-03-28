<?php

namespace IHorse\BackendBundle\Twig\Extension;
use Twig_Environment as Environment;

use IHorse\BackendBundle\Twig\Extension\IHorseExtension;

class DrawingboardExtension extends IHorseExtension
{

    protected $twig;
    protected $assetFunction;

    public function initRuntime(Environment $twig)
    {
        $this->twig = $twig;
    }

    protected function asset($asset)
    {
        if (empty($this->assetFunction)) {
             $this->assetFunction = $this->twig->getFunction('asset')->getCallable();
        }
        return call_user_func($this->assetFunction, $asset);
    }

    public function getFunctions()
    {
        return array('initDrawingboard' => new \Twig_Function_Method($this, 'initDrawingboard'));
    }

    public function initDrawingboard($id, $type = 'simple', $options = array(), $html_options = null)
    {

        $out = $this->_loadInitCSS();
        $out .= $this->_loadInitJS();
        $out .= '<div id="'.$id.'"';
        if(!empty($html_options)){
            foreach($html_options as $k => $v){
                $out .= $k."=\"".$v."\" ";
            }
        }
        $out .= '></div>';
        $out .= $this->_loadDrawingboardJS($id, $type, $options);        
        return $out;
    }

    private function _loadInitCSS(){
        return '<link rel="stylesheet" href="'.$this->asset('bundles/backend/css/drawingboard/drawingboard.min.css').'">';
    }

    private function _loadInitJS(){
        return '<script src="'.$this->asset('bundles/backend/js/drawingboard/drawingboard.js').'"></script>';
    }

    private function _loadDrawingboardJS($id, $type = 'simple', $options){

        $_default_simple = array('controls' => array('Size' => array('type' => 'dropdown'),
                                                     'Navigation',
                                                     'DrawingMode' => array('filler' => false)), 
                                 'size' => 2, 
                                 'webStorage' => false, 
                                 'background' => 'transparent');

        $_default_complete = array('controls' => array('Color', 'DentalHorse'));

        if($type == 'simple'){
            $_options = array_merge($_default_simple, $options);
        }else{
            $_options = array_merge($_default_simple, $_default_complete);
            $_options = array_merge($_options, $options);
        }

        $out = '<script>var simpleBoard = new DrawingBoard.Board(\''.$id.'\', {';
        $c1 = '';
        foreach ($_options as $k1 => $v1) {//primer nivel
            $out .= $c1.'\''.$k1.'\''.':';
            if(is_array($v1)){
                $c2 = '';
                $out .= '[';
                foreach ($v1 as $k2 => $v2) {//segundo nivel
                    $out .= $c2;
                    if(is_numeric($k2)){
                        $out .= '\''.$v2.'\'';
                    }else{
                        if(is_array($v2)){
                            $out .= '{\''.$k2.'\':';
                            $c3 = '';
                            $out .= '{';
                            foreach ($v2 as $k3 => $v3) {//tercer nivel
                                $out .= $c3;
                                $out .= '\''.$k3.'\':\''.$v3.'\'';    
                                $c3 = ',';
                            }
                            $out .= '}';
                        }else{
                            $out .= '{\''.$v2.'\':';
                        }
                        $out .= '}';
                    }
                    $c2 = ',';
                }
                $out .= ']';
            }else{
                $out .= '\''.$v1.'\'';
            }
            $c1 = ',';
        }

        $out .='});</script>';
        return $out;
    }

    public function getName()
    {
        return 'drawingboard_extension';
    }
    
}
