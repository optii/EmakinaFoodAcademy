<?php

namespace DamDan\AdminBundle\Controller;

use DamDan\AppBundle\Entity\Allergen;
use DamDan\AppBundle\Form\Type\AllergenType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/allergen")
 */
class AllergenController extends Controller
{
    /**
     * Lists all allergen entities.
     *
     * @Route("/", name="admin_allergen_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $allergens = $em->getRepository('DamDanAppBundle:Allergen')->findAll();
        if($request->isXmlHttpRequest()){
            return $this->json($allergens);
        }

        return $this->render('DamDanAdminBundle:allergen:index.html.twig', array('allergens' => $allergens));
    }

    /**
     * Creates a new dish entity.
     *
     * @Route("/new", name="admin_allergen_new")
     * @Method({"GET", "POST"})
     * @Security("is_granted('ROLE_EDITOR')")
     */
    public function newAction(Request $request)
    {
        $allergen = new Allergen();
        $form = $this->createForm(AllergenType::class, $allergen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($allergen);
            $em->flush($allergen);

            if($request->isXmlHttpRequest()){
                return $this->json($allergen);
            }

            return $this->redirectToRoute('admin_allergen_index');
        }

        if($request->isXmlHttpRequest()){
            return $this->json(['error' => 'bad request'], 500);
        }

        return $this->render('DamDanAdminBundle:allergen:new.html.twig', array('form' => $form->createView()));
    }

    /**
     * Deletes an allergen entity.
     *
     * @Route("/{id}", name="admin_allergen_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_CHEF')")
     */
    public function deleteAction(Request $request, Allergen $allergen)
    {
        $form = $this->createDeleteForm($allergen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($allergen);
            $em->flush($allergen);
        }

        return $this->redirectToRoute('admin_allergen_index');
    }

    public function listDeleteAction(Allergen $allergen){
        $form = $this->createDeleteForm($allergen);
        return $this->render('DamDanAdminBundle:allergen:delete.html.twig', array('form' => $form->createView()));
    }

    /**
     * Creates a form to delete an allergen entity.
     *
     * @param Allergen $allergen The allergen entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Allergen $allergen)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_allergen_delete', array('id' => $allergen->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}
