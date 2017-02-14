<?php

namespace DamDan\AdminBundle\Controller;

use DamDan\AppBundle\Entity\Menu;
use DamDan\AppBundle\Services\Paginator;
use DamDan\AppBundle\Form\Type\MenuType;
use DamDan\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Menu controller.
 *
 * @Route("menu")
 */
class MenuController extends Controller
{
    /**
     * Lists all menu entities.
     *
     * @Route("/", name="admin_menu_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $menus = $em->getRepository('DamDanAppBundle:Menu')->findByNotStatus(Menu::STATUS_DRAFT);

        $paginator = new Paginator($menus, 10, $request->query->get('page', 1));

        return $this->render('DamDanAdminBundle:menu:index.html.twig', array(
            'menus' => $paginator,
        ));
    }

    /**
     * Lists draft menu entities.
     *
     * @Route("/draft", name="admin_menu_draft")
     * @Method("GET")
     */
    public function draftAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $menus = $em->getRepository('DamDanAppBundle:Menu')->findBy(array('status' => Menu::STATUS_DRAFT, 'author' => $this->getUser()));

        $paginator = new Paginator($menus, 10, $request->query->get('page', 1));

        return $this->render('DamDanAdminBundle:menu:drafts.html.twig', array(
            'menus' => $paginator,
        ));
    }

    /**
     * @Route("/publish/{id}", name="admin_menu_publish")
     * @Method("POST")
     */
    public function publishAction(Request $request, Menu $menu){
        $form = $this->createPublishForm($menu);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            if($menu->getAuthor() != $this->getUser()){
                return $this->createAccessDeniedException('You can\'t publish someone elses menu');
            }

            if($menu->getStatus() != Menu::STATUS_DRAFT){
                return $this->createNotFoundException("Draft menu not found");
            }

            $menu->setStatus(Menu::STATUS_IN_VALIDATION);
            $em->persist($menu);
            $em->flush();

            $this->addFlash('success', 'Your menu has been published for validation');
            return $this->redirectToRoute('admin_menu_draft');
        }


        return $this->render('DamDanAdminBundle:menu:publish.html.twig', array('form' => $form->createView()));
    }

    /**
     * Creates a new menu entity.
     *
     * @Route("/new", name="admin_menu_new")
     * @Method({"GET", "POST"})
     * @Security("is_granted('ROLE_EDITOR')")
     */
    public function newAction(Request $request)
    {
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $menu->setAuthor($this->getUser());
            $em->persist($menu);
            $em->flush($menu);

            if($this->getUser()->hasRole('ROLE_EDITOR')){
                $emails = $em->getRepository('DamDanUserBundle:User')->findEmailsByRoles([User::ROLE_CHEF, User::ROLE_REVIEWER]);
                $this->sendReviewEmail($menu, $emails);
            }

            return $this->redirectToRoute('admin_menu_show', array('id' => $menu->getId()));
        }

        return $this->render('DamDanAdminBundle:menu:new.html.twig', array(
            'menu' => $menu,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a menu entity.
     *
     * @Route("/{id}", name="admin_menu_show")
     * @Method("GET")
     */
    public function showAction(Menu $menu)
    {
        $deleteForm = $this->createDeleteForm($menu);

        return $this->render('DamDanAdminBundle:menu:show.html.twig', array(
            'menu' => $menu,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing menu entity.
     *
     * @Route("/{id}/edit", name="admin_menu_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('ROLE_EDITOR')")
     */
    public function editAction(Request $request, Menu $menu)
    {
        $deleteForm = $this->createDeleteForm($menu);
        $editForm = $this->createForm(MenuType::class, $menu);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            if($this->getUser()->hasRole('ROLE_EDITOR')){
                $emails = $em->getRepository('DamDanUserBundle:User')->findEmailsByRoles([User::ROLE_CHEF, User::ROLE_REVIEWER]);
                $this->sendReviewEmail($menu, $emails);
            }

            return $this->redirectToRoute('admin_menu_edit', array('id' => $menu->getId()));
        }

        return $this->render('DamDanAdminBundle:menu:edit.html.twig', array(
            'menu' => $menu,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a menu entity.
     *
     * @Route("/{id}", name="admin_menu_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_CHEF')")
     */
    public function deleteAction(Request $request, Menu $menu)
    {
        $form = $this->createDeleteForm($menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($menu);
            $em->flush($menu);
        }

        return $this->redirectToRoute('admin_menu_index');
    }

    /**
     * Creates a form to delete a menu entity.
     *
     * @param Menu $menu The menu entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Menu $menu)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_menu_delete', array('id' => $menu->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Creates a form to publish the menu
     *
     * @param Menu $menu
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    private function createPublishForm(Menu $menu){
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_menu_publish', array('id' => $menu->getId())))
            ->setMethod('POST')
            ->getForm()
        ;
    }


    /**
     * Sends the review email
     *
     * @param Menu $menu
     * @param $emails
     */
    private function sendReviewEmail(Menu $menu, $emails)
    {
        $message = \Swift_Message::newInstance()
            ->setFrom($this->getUser()->getEmail())
            ->setTo($emails)
            ->setSubject(sprintf('[MENU REVIEW] %s needs reviewing - {emakina food academy}', $menu->getTitle()))
            ->setBody($this->render('DamDanAdminBundle:emails:menu_review.html.twig', array('menu' => $menu)),
                'text/html'
            );

        $this->get('mailer')->send($message);
    }
}
