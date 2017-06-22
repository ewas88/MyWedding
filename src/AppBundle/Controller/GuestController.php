<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Guest;
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
class GuestController extends Controller
{
    /**
     * @Route("/guests")
     * @Template("AppBundle:Admin:guest.html.twig")
     * @Method("GET")
     */
    public function guestsAction()
    {
        $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
        $guests = $guestRepository->findBy(array(), array('invitation' => 'ASC', 'name' => 'ASC'));

        return ['guests' => $guests];
    }

    /**
     * @Route("/guests")
     * @Template("AppBundle:Admin:guest.html.twig")
     * @Method("POST")
     */
    public function newGuestAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $name = $request->request->get('name');
        $surname = $request->request->get('surname');
        $email = $request->request->get('email');
        $isSingle = $request->request->get('is_single');
        $invite = $request->request->get('invite');

        if ($invite == "") {
            $invitation = null;
        } else {

            $inviteRepository = $this->getDoctrine()->getRepository('AppBundle:Invitation');
            $invitation = $inviteRepository->find($invite);
        }

        if ($isSingle == 2) {
            $isSingle = null;
        }

        $guest = new Guest();
        $guest->setName($name);
        $guest->setSurname($surname);
        $guest->setEmail($email);
        $guest->setIsSingle($isSingle);
        $guest->setInvitation($invitation);

        $em->persist($guest);
        $em->flush();

        $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
        $guests = $guestRepository->findBy(array(), array('invitation' => 'ASC', 'name' => 'ASC'));

        return ['guests' => $guests];
    }

    /**
     * @Route("/guests/delete/{id}", name="delete_guest")
     * @Template("AppBundle:Admin:guest.html.twig")
     */
    public function deleteGuestAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
        $guest = $guestRepository->find($id);

        if ($guest) {
            $em->remove($guest);
            $em->flush();
        }

        $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
        $guests = $guestRepository->findBy(array(), array('invitation' => 'ASC', 'name' => 'ASC'));

        return ['guests' => $guests];
    }

}
