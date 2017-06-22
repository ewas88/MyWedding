<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Present;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin")
 * @Security("has_role('ROLE_USER')")
 */
class PresentController extends Controller
{
    /**
     * @Route("/presents")
     * @Template("AppBundle:Admin:present.html.twig")
     * @Method("GET")
     */
    public function presentAction()
    {
        $presentsRepository = $this->getDoctrine()->getRepository('AppBundle:Present');
        $presents = $presentsRepository->findBy(array(), array('name' => 'ASC'));

        return ['presents' => $presents];
    }

    /**
     * @Route("/presents")
     * @Template("AppBundle:Admin:present.html.twig")
     * @Method("POST")
     */
    public function newPresentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $name = $request->request->get('name');
        $price = $request->request->get('price');
        $link = $request->request->get('link');

        $present = new Present();
        $present->setName($name);
        $present->setPrice($price);
        $present->setLink($link);
        $present->setInvitation(null);

        $em->persist($present);
        $em->flush();

        $presentsRepository = $this->getDoctrine()->getRepository('AppBundle:Present');
        $presents = $presentsRepository->findBy(array(), array('name' => 'ASC'));

        return ['presents' => $presents];
    }

    /**
     * @Route("/presents/delete/{id}", name="delete_present")
     * @Template("AppBundle:Admin:present.html.twig")
     */
    public function deletePresentAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $presentRepository = $this->getDoctrine()->getRepository('AppBundle:Present');
        $present = $presentRepository->find($id);

        if ($present) {
            $em->remove($present);
            $em->flush();
        }

        $presentsRepository = $this->getDoctrine()->getRepository('AppBundle:Present');
        $presents = $presentsRepository->findBy(array(), array('name' => 'ASC'));

        return ['presents' => $presents];
    }

}
