<?php

namespace AppBundle\Controller;

//use AppBundle\Entity\Guest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\Session;

class WeddingController extends Controller
{
    /**
     * @Route("/")
     * @Method("GET")
     */
    public function mainSiteAction(Request $request)
    {
        $weddingRepository = $this->getDoctrine()->getRepository('AppBundle:WeddingInfo');
        $wedding = $weddingRepository->find(1);

        if (isset($_SESSION)) {
            $datetime1 = strtotime(date('Y-m-d'));
            $datetime2 = strtotime($wedding->getWeddingDate()->format('Y-m-d'));
            $days = ($datetime2 - $datetime1) / 86400;

            return $this->render('AppBundle:Wedding:base.html.twig', array(
                'days' => $days, 'wedding' => $wedding
            ));
        } else {
            return $this->render('AppBundle:Wedding:noguest.html.twig', array(
                'wedding' => $wedding, 'code' => 1
            ));
        }
    }

    /**
     * @Route("/")
     * @Method("POST")
     */
    public function guestSiteAction(Request $request)
    {
        $code = $request->request->get('code');
        $invRepository = $this->getDoctrine()->getRepository('AppBundle:Invitation');
        $invitation = $invRepository->findOneBy(array('code' => $code));

        $weddingRepository = $this->getDoctrine()->getRepository('AppBundle:WeddingInfo');
        $wedding = $weddingRepository->find(1);

        if (!$invitation) {

            return $this->render('AppBundle:Wedding:noguest.html.twig', array(
                'wedding' => $wedding
            ));
        } else {

            $session = $request->getSession();
            $session->set('name', $invitation->getId());
            $session->save();

            $datetime1 = strtotime(date('Y-m-d'));
            $datetime2 = strtotime($wedding->getWeddingDate()->format('Y-m-d'));
            $days = ($datetime2 - $datetime1) / 86400;

            return $this->render('AppBundle:Wedding:base.html.twig', array(
                'days' => $days, 'wedding' => $wedding
            ));
        }
    }

    /**
     * @Route("/wedding")
     * @Template("AppBundle:Wedding:info.html.twig")
     * @Method("GET")
     */
    public function infoAction()
    {
        $weddingRepository = $this->getDoctrine()->getRepository('AppBundle:WeddingInfo');
        $wedding = $weddingRepository->find(1);

        return ['wedding' => $wedding];
    }

    /**
     * @Route("/welovepresents")
     * @Template("AppBundle:Wedding:presents.html.twig")
     */
    public function choosePresentsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $takePresent = $request->request->get('present');
        $dropPresent = $request->request->get('resign');

        if ($takePresent) {
            $presentRepository = $this->getDoctrine()->getRepository('AppBundle:Present');
            $present = $presentRepository->find($takePresent);

            $session = $request->getSession();
            $inviteId = $session->get('name');

            $inviteRepository = $this->getDoctrine()->getRepository('AppBundle:Invitation');
            $invitation = $inviteRepository->find($inviteId);

            $present->setInvitation($invitation);
            $em->persist($present);
            $em->flush();
        }

        if ($dropPresent) {
            $presentRepository = $this->getDoctrine()->getRepository('AppBundle:Present');
            $present = $presentRepository->find($dropPresent);

            $present->setInvitation(NULL);
            $em->persist($present);
            $em->flush();
        }

        $presentRepository = $this->getDoctrine()->getRepository('AppBundle:Present');
        $presents = $presentRepository->findBy(array('invitation' => NULL));

        $session = $request->getSession();
        $inviteId = $session->get('name');
        $myPresents = $presentRepository->findBy(array('invitation' => $inviteId));

        return ['presents' => $presents, 'mypresents' => $myPresents];
    }


    /**
     * @Route("/rsvp")
     * @Template("AppBundle:Wedding:rsvp.html.twig")
     */
    public function checkCodeAction(Request $request)
    {
        $session = $request->getSession();
        $inviteId = $session->get('name');

        $invRepository = $this->getDoctrine()->getRepository('AppBundle:Invitation');
        $invitation = $invRepository->find($inviteId);

        return ['invitation' => $invitation];
    }

    /**
     * @Route("/rsvp/{id}")
     * @Template("AppBundle:Wedding:check.html.twig")
     * @Method("POST")
     */
    public function responseAction(Request $request, $id)
    {

        $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
        $guests = $guestRepository->getByCode($id);


        foreach ($guests as $guest) {

            $em = $this->getDoctrine()->getManager();

            $a = $guest . '-answer';
            $b = $guest . '-bus';
            $c = $guest . '-hotel';

            $isComing = $request->request->get($a);
            $isBus = $request->request->get($b);
            $isHotel = $request->request->get($c);

            $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
            $guest2 = $guestRepository->find($guest);

            $guest2->setIsConfirmed(1);
            $guest2->setIsComing($isComing);
            $guest2->setIsBus($isBus);
            $guest2->setIsHotel($isHotel);

            $em->persist($guest2);
            $em->flush();
        }

        return [];
    }

}
