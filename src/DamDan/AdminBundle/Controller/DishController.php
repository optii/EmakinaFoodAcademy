<?php

namespace DamDan\AdminBundle\Controller;

use DamDan\AppBundle\Entity\Dish;
use DamDan\AppBundle\Form\Type\AllergenType;
use DamDan\AppBundle\Form\Type\DishType;
use DamDan\AppBundle\Services\Paginator;
use DamDan\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * Dish controller.
 *
 * @Route("dish")
 */
class DishController extends Controller
{
    /**
     * Lists all dish entities.
     *
     * @Route("/", name="admin_dish_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $dishes = $em->getRepository('DamDanAppBundle:Dish')->findByNotStatus(Dish::STATUS_DRAFT);
        $paginator = new Paginator($dishes, 10, $request->query->get('page', 1));

        return $this->render('DamDanAdminBundle:dish:index.html.twig', array(
            'dishes' => $paginator
        ));
    }

    /**
     * Lists all draft dish entities.
     *
     * @Route("/drafts", name="admin_dish_draft")
     * @Method("GET")
     */
    public function draftAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $dishes = $em->getRepository('DamDanAppBundle:Dish')->findBy(array('status' => Dish::STATUS_DRAFT, 'author' => $this->getUser()));
        $paginator = new Paginator($dishes, 10, $request->query->get('page', 1));

        return $this->render('DamDanAdminBundle:dish:drafts.html.twig', array(
            'dishes' => $paginator,
        ));
    }


    /**
     * @Route("/publish/{id}", name="admin_dish_publish")
     * @Method({"GET", "POST"})
     */
    public function publishAction(Request $request, Dish $dish){
        $form = $this->createPublishForm($dish);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            if($dish->getAuthor() != $this->getUser()){
                return $this->createAccessDeniedException('You can\'t publish someone else\'s dish');
            }

            if($dish->getStatus() != Dish::STATUS_DRAFT){
                return $this->createNotFoundException("Draft menu not found");
            }

            $dish->setStatus(Dish::STATUS_IN_VALIDATION);
            $em->persist($dish);
            $em->flush();

            $this->addFlash('success', 'Your dish has been published for validation');
            return $this->redirectToRoute('admin_dish_draft');
        }


        return $this->render('DamDanAdminBundle:dish:publish.html.twig', array('form' => $form->createView()));
    }

    /**
     * Creates a new dish entity.
     *
     * @Route("/new", name="admin_dish_new")
     * @Method({"GET", "POST"})
     * @Security("is_granted('ROLE_EDITOR')")
     */
    public function newAction(Request $request)
    {
        $dish = new Dish();
        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dish->setAuthor($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($dish);
            $em->flush($dish);

            if($this->getUser()->hasRole('ROLE_EDITOR')){
                $emails = $em->getRepository('DamDanUserBundle:User')->findEmailsByRoles([User::ROLE_CHEF, User::ROLE_REVIEWER]);
                $this->sendReviewEmail($dish, $emails);
            }

            return $this->redirectToRoute('admin_dish_show', array('id' => $dish->getId()));
        }

        $allergenForm = $this->createForm(AllergenType::class, null, [
            'action' => $this->generateUrl('admin_allergen_new'),
            'method' => 'POST',
        ]);

        return $this->render('DamDanAdminBundle:dish:new.html.twig', array(
            'dish' => $dish,
            'form' => $form->createView(),
            'allergen_form' => $allergenForm->createView()
        ));
    }

    /**
     * Finds and displays a dish entity.
     *
     * @Route("/{id}", name="admin_dish_show")
     * @Method("GET")
     */
    public function showAction(Dish $dish)
    {
        $deleteForm = $this->createDeleteForm($dish);

        return $this->render('DamDanAdminBundle:dish:show.html.twig', array(
            'dish' => $dish,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing dish entity.
     *
     * @Route("/{id}/edit", name="admin_dish_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('ROLE_EDITOR')")
     */
    public function editAction(Request $request, Dish $dish)
    {
        $deleteForm = $this->createDeleteForm($dish);
        $editForm = $this->createForm(DishType::class, $dish);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            if($this->getUser()->hasRole('ROLE_EDITOR')){
                $emails = $em->getRepository('DamDanUserBundle:User')->findEmailsByRoles([User::ROLE_CHEF, User::ROLE_REVIEWER]);
                $this->sendReviewEmail($dish, $emails);
            }

            return $this->redirectToRoute('admin_dish_show', array('id' => $dish->getId()));
        }

        $allergenForm = $this->createForm(AllergenType::class, null, [
            'action' => $this->generateUrl('admin_allergen_new'),
            'method' => 'POST',
        ]);


        return $this->render('DamDanAdminBundle:dish:edit.html.twig', array(
            'dish' => $dish,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'allergen_form' => $allergenForm->createView()
        ));
    }

    /**
     * Deletes a dish entity.
     *
     * @Route("/{id}", name="admin_dish_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_CHEF')")
     */
    public function deleteAction(Request $request, Dish $dish)
    {
        $form = $this->createDeleteForm($dish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($dish);
            $em->flush($dish);
        }

        return $this->redirectToRoute('admin_dish_index');
    }

    /**
     * Creates a form to delete a dish entity.
     *
     * @param Dish $dish The dish entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Dish $dish)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_dish_delete', array('id' => $dish->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Creates a form to publish the dish
     *
     * @param Dish $dish
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    private function createPublishForm(Dish $dish){
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_dish_publish', array('id' => $dish->getId())))
            ->setMethod('POST')
            ->getForm()
        ;
    }

    /**
     * Sends the review email
     *
     * @param Dish $dish
     * @param $emails
     */
    private function sendReviewEmail(Dish $dish, $emails)
    {
        $message = \Swift_Message::newInstance()
            ->setFrom($this->getUser()->getEmail())
            ->setTo($emails)
            ->setSubject(sprintf('[DISH REVIEW] %s needs reviewing - {emakina food academy}', $dish->getTitle()))
            ->setBody($this->render('DamDanAdminBundle:emails:dish_review.html.twig', array('dish' => $dish)),
                'text/html'
            )
            ->addPart(
                $this->render('DamDanAdminBundle:emails:dish_review.txt.twig', array('dish' => $dish)),
                'text/plain'
            );

        $this->get('mailer')->send($message);
    }
}
