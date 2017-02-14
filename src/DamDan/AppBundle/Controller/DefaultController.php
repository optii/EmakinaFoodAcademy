<?php

namespace DamDan\AppBundle\Controller;

use DamDan\AppBundle\Entity\Dish;
use DamDan\AppBundle\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $menus = $em->getRepository('DamDanAppBundle:Menu')->findByStatus(Menu::STATUS_ACCEPTED, array('order' => 'ASC'), 3);
        $dishes = $em->getRepository('DamDanAppBundle:Dish')->findByStatus(Dish::STATUS_ACCEPTED);
        // Shuffle the dishes so as to not always show the same ones
        shuffle($dishes);
        // Use on the first 3
        $dishes = array_slice($dishes, 0, 3);


        return $this->render('DamDanAppBundle:Default:index.html.twig', array('menus' => $menus, 'dishes' => $dishes));
    }

    /**
     * @Route("/conditions", name="conditions")
     */
     public function conditionsAction(){
        return $this->render("DamDanAppBundle:Default:conditions.html.twig");
     }

    /**
     * @Route("/privacy", name="privacy")
     */
    public function privacyAction(){
        return $this->render("DamDanAppBundle:Default:privacy.html.twig");
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction(){
        return $this->render("DamDanAppBundle:Default:contact.html.twig");
    }
}
