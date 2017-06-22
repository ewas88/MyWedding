<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Invitation;
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
class InvitationController extends Controller
{
    /**
     * @Route("/invitations")
     * @Template("AppBundle:Admin:invitation.html.twig")
     * @Method("GET")
     */
    public function invitationAction()
    {
        $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
        $guests = $guestRepository->findBy(array('invitation' => null));
        $people = $guestRepository->findAll();

        $inviteRepository = $this->getDoctrine()->getRepository('AppBundle:Invitation');
        $invitations = $inviteRepository->findBy(array(), array('id' => 'ASC')
        );

        return ['guests' => $guests, 'invitations' => $invitations, 'people' => $people];
    }

    /**
     * @Route("/invitations")
     * @Template("AppBundle:Admin:invitation.html.twig")
     * @Method("POST")
     */
    public function newInvitationAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $invitation = new Invitation();
        $invitation->setCode();
        $em->persist($invitation);
        $em->flush();

        $guests = $request->request->get('guest_list');

        if (!$guests == null) {

            foreach ($guests as $guest) {
                $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
                $guest2 = $guestRepository->find($guest);
                $guest2->setInvitation($invitation);
                $em->persist($guest2);
                $em->flush();
            }
        }

        $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
        $guests = $guestRepository->findBy(array('invitation' => null));
        $people = $guestRepository->findAll();

        $inviteRepository = $this->getDoctrine()->getRepository('AppBundle:Invitation');
        $invitations = $inviteRepository->findBy(array(), array('id' => 'ASC')
        );

        return ['guests' => $guests, 'invitations' => $invitations, 'people' => $people];
    }

    /**
     * @Route("/invitations/delete/{id}", name="delete_invite")
     * @Template("AppBundle:Admin:invitation.html.twig")
     */
    public function deleteInviteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $inviteRepository = $this->getDoctrine()->getRepository('AppBundle:Invitation');
        $invite = $inviteRepository->find($id);

        if ($invite) {
            $em->remove($invite);
            $em->flush();
        }

        $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
        $guests = $guestRepository->findBy(array('invitation' => null));
        $people = $guestRepository->findAll();

        $inviteRepository = $this->getDoctrine()->getRepository('AppBundle:Invitation');
        $invitations = $inviteRepository->findBy(array(), array('id' => 'ASC')
        );

        return ['guests' => $guests, 'invitations' => $invitations, 'people' => $people];
    }
}
