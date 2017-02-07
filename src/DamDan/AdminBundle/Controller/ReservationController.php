<?php
/**
 * Created by PhpStorm.
 * User: Damien
 * Date: 06/02/2017
 * Time: 15:35
 */

namespace DamDan\AdminBundle\Controller;

use DamDan\AppBundle\Entity\Reservation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Menu controller.
 *
 * @Route("reservation")
 */
class ReservationController extends Controller
{
    /**
     * Lists all reservation entities.
     *
     * @Route("/", name="admin_reservation_index")
     * @Method("GET")
     * @Security("is_granted('ROLE_SERVER')")
     */
    public function indexAction()
    {
        $nbUnconfirmed = 0;
        $em = $this->getDoctrine()->getManager();

        $reservations = $em->getRepository('DamDanAppBundle:Reservation')->findBy(array(), array('id' => 'DESC'));

        foreach ($reservations as $reservation){
            if($reservation->getAccepted() == Reservation::STATUS_PENDING){
                ++$nbUnconfirmed;
            }
        }
        if($nbUnconfirmed > 0) {
            $this->addFlash('warning', 'You have '.$nbUnconfirmed.' unconfirmed reservation(s)');
        }
        return $this->render('DamDanAdminBundle:reservation:index.html.twig', array(
            'reservations' => $reservations,
        ));
    }

    /**
     * Accept a reservation
     *
     * @Route("/accept/{id}", name="accept_reservation")
     * @Method("POST")
     * @Security("is_granted('ROLE_SERVER')")
     */
     public function acceptAction(Request $request)
     {
         $repository = $this
             ->getDoctrine()
             ->getManager()
             ->getRepository('DamDanAppBundle:Reservation');

         $reservation = $repository->find($request->get('id'));
         $reservation->setAccepted(Reservation::STATUS_ACCEPTED);
         $em = $this->getDoctrine()->getManager();
         $em->persist($reservation);
         $em->flush();
        return $this->redirectToRoute('admin_reservation_index');
     }

    /**
     * Refuse a reservation
     *
     * @Route("/refuse/{id}", name="refuse_reservation")
     * @Method("POST")
     * @Security("is_granted('ROLE_SERVER')")
     */
     public function refuseAction(Request $request)
     {
         $repository = $this
             ->getDoctrine()
             ->getManager()
             ->getRepository('DamDanAppBundle:Reservation');

         $reservation = $repository->find($request->get('id'));
         $reservation->setAccepted(Reservation::STATUS_REFUSED);
         $em = $this->getDoctrine()->getManager();
         $em->persist($reservation);
         $em->flush();
         return $this->redirectToRoute('admin_reservation_index');
     }

}