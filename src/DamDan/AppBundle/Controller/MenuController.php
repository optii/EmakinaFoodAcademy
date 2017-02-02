<?php

namespace DamDan\AppBundle\Controller;

use DamDan\AppBundle\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MenuController extends Controller
{
    /**
     * @Route("/menus", name="menu_index")
     */
    public function indexAction()
    {
        return $this->render('DamDanAppBundle:menu:index.html.twig');
    }

    /**
     * @Route("/menu/{id}", name="menu_show")
     */
    public function showAction(Menu $menu)
    {
        return $this->render('DamDanAppBundle:menu:show.html.twig', array('menu' => $menu));
    }
}
