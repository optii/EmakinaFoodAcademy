<?php

namespace DamDan\AdminBundle\Controller;

use DamDan\AppBundle\Entity\Allergen;
use DamDan\AppBundle\Form\AllergenType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $allergens = $em->getRepository('DamDanAppBundle:Allergen')->findAll();
        return $this->json($allergens);
    }

    /**
     * Creates a new dish entity.
     *
     * @Route("/new", name="admin_allergen_new")
     * @Method({"POST"})
     * @Security("is_granted('ROLE_EDITOR')")
     */
    public function newAction(Request $request)
    {
        if(!$request->isXmlHttpRequest()){
            return $this->json(['message' => 'You can only access this through Ajax'], 400);
        }

        $allergen = new Allergen();
        $form = $this->createForm(AllergenType::class, $allergen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($allergen);
            $em->flush($allergen);

            return $this->json($allergen);
        }

        return $this->json(['error' => 'bad request'], 500);
    }
}
