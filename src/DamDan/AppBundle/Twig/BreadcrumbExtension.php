<?php

namespace DamDan\AppBundle\Twig;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class BreadcrumbExtension
 * @package DamDan\AppBundle\Twig
 * @author Daniel Cole <daniel@dancole.fr>
 */
class BreadcrumbExtension extends \Twig_Extension
{

    /** @var UrlGeneratorInterface  */
    private $generator;

    public function __construct(UrlGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('breadcrumb', array($this, 'breadcrumbFunction'), array(
                'is_safe' => array('html')
            )),
        );
    }

    public function breadcrumbFunction($array = array(), $divider = '/', $ucfirst = true){
        $breadcrumb = '<div class="ui breadcrumb">';
        $i = 0;
        foreach ($array as $route => $value){
            if(is_array($value)){
                $name = $value[0];
                $url = $this->generator->generate($route, $value[1]);
            } else {
                $url = $this->generator->generate($route);
                $name = $value;
            }

            if($ucfirst){
                $name = ucfirst($name);
            }

            if($i+1 != count($array)){
                $breadcrumb .= '<a class="section" href="'.$url.'">'.$name.'</a>';
                $breadcrumb .= '<span class="divider">'.$divider.'</span>';
            } else {
                $breadcrumb .= '<div class="active section">'.$name.'</div>';
            }
            ++$i;
        }

        $breadcrumb .= '</div>';

        return $breadcrumb;
    }

    public function getName()
    {
        return 'breadcrumb_extension';
    }
}
