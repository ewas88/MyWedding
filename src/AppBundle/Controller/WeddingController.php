<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Guest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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

        if (!$inviteId) {
            return [];

        } else {
            $myPresents = $presentRepository->findBy(array('invitation' => $inviteId));

            return ['presents' => $presents, 'mypresents' => $myPresents];
        }
    }


    /**
     * @Route("/rsvp")
     * @Template("AppBundle:Wedding:rsvp.html.twig")
     * @Method("GET")
     */
    public function answersAction(Request $request)
    {
        $session = $request->getSession();
        $inviteId = $session->get('name');

        if (!$inviteId) {
            return [];

        } else {

            $invRepository = $this->getDoctrine()->getRepository('AppBundle:Invitation');
            $invitation = $invRepository->find($inviteId);


            return ['invitation' => $invitation];
        }
    }

    /**
     * @Route("/rsvp")
     * @Template("AppBundle:Wedding:check.html.twig")
     * @Method("POST")
     */
    public function responseAction(Request $request)
    {
        $session = $request->getSession();
        $inviteId = $session->get('name');
        $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
        $guests = $guestRepository->findBy(array('invitation' => $inviteId));

        if (empty($request->request->get('date_name'))) {

            foreach ($guests as $person) {

                $person = $person->getId();

                $em = $this->getDoctrine()->getManager();

                $a = $person . '-answer';
                $b = $person . '-bus';
                $c = $person . '-hotel';

                $isComing = $request->request->get($a);
                $isBus = $request->request->get($b);
                $isHotel = $request->request->get($c);

                $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
                $guest = $guestRepository->find($person);

                $guest->setIsConfirmed(1);
                $guest->setIsComing($isComing);
                $guest->setIsTransport($isBus);
                $guest->setIsAccomodation($isHotel);

                $em->persist($guest);
                $em->flush();
            }

        } else {
            foreach ($guests as $person) {

                $person = $person->getId();

                $em = $this->getDoctrine()->getManager();

                $a = $person . '-answer';
                $b = $person . '-bus';
                $c = $person . '-hotel';

                $isComing = $request->request->get($a);
                $isBus = $request->request->get($b);
                $isHotel = $request->request->get($c);

                $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
                $guest = $guestRepository->find($person);

                $guest->setIsConfirmed(1);
                $guest->setIsComing($isComing);
                $guest->setIsTransport($isBus);
                $guest->setIsAccomodation($isHotel);
                $guest->setIsSingle(0);

                $em->persist($guest);
                $em->flush();

                $name = $request->request->get('date_name');
                $surname = $request->request->get('date_surname');
                $email = $request->request->get('date_email');
                $isDateBus = $request->request->get('x-bus');
                $isDateHotel = $request->request->get('x-hotel');

                if ($email == "") {
                    $email = $guest->getEmail();
                }

                $invRepository = $this->getDoctrine()->getRepository('AppBundle:Invitation');
                $invitation = $invRepository->find($inviteId);

                $guest2 = new Guest();
                $guest2->setName($name);
                $guest2->setSurname($surname);
                $guest2->setEmail($email);
                $guest2->setIsSingle(0);
                $guest2->setInvitation($invitation);
                $guest2->setIsConfirmed(1);
                $guest2->setIsComing(1);
                $guest2->setIsTransport($isDateBus);
                $guest2->setIsAccomodation($isDateHotel);

                $em->persist($guest2);
                $em->flush();

            }

        }
        return [];
    }
}
