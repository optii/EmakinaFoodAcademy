<?php

namespace DamDan\AppBundle\Controller;

use DamDan\AppBundle\Entity\Dish;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DishController extends Controller
{
    /**
     * @Route("/dishes", name="dish_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $dishes = $em->getRepository('DamDanAppBundle:Dish')->findByStatus(Dish::STATUS_ACCEPTED);
        return $this->render('DamDanAppBundle:dish:index.html.twig', array('dishes' => $dishes));
    }

    /**
     * @Route("/dish/{id}", name="dish_show")
     */
    public function showAction(Dish $dish){
        return $this->render('DamDanAppBundle:dish:show.html.twig', array('dish' => $dish));
    }
}
