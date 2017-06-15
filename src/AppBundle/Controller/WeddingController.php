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

        if (isset($_SESSION)) {
        $weddingRepository = $this->getDoctrine()->getRepository('AppBundle:WeddingInfo');
        $wedding = $weddingRepository->find(1);

        $datetime1 = strtotime(date('Y-m-d'));
        $datetime2 = strtotime($wedding->getWeddingDate()->format('Y-m-d'));
        $days = ($datetime2 - $datetime1) / 86400;

        return $this->render('AppBundle:Wedding:base.html.twig', array(
            'days' => $days, 'wedding' => $wedding
        ));

        } else {
            $weddingRepository = $this->getDoctrine()->getRepository('AppBundle:WeddingInfo');
            $wedding = $weddingRepository->find(1);

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

        if (!$invitation) {
            $weddingRepository = $this->getDoctrine()->getRepository('AppBundle:WeddingInfo');
            $wedding = $weddingRepository->find(1);

            return $this->render('AppBundle:Wedding:noguest.html.twig', array(
                'wedding' => $wedding
            ));
        } else {
            $session = new Session();
            $session->set('name', $code);

            $weddingRepository = $this->getDoctrine()->getRepository('AppBundle:WeddingInfo');
            $wedding = $weddingRepository->find(1);

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
    public function presentsAction()
    {
        $presentRepository = $this->getDoctrine()->getRepository('AppBundle:Present');
        $presents = $presentRepository->findBy(array('isTaken' => 0));

        return ['presents' => $presents];
    }

    /**
     * @Route("/rsvp")
     * @Template("AppBundle:Wedding:code.html.twig")
     * @Method("GET")
     */
    public function codeAction()
    {
        return [];
    }

    /**
     * @Route("/rsvp")
     * @Template("AppBundle:Wedding:rsvp.html.twig")
     * @Method("POST")
     */
    public function checkCodeAction(Request $request)
    {
        $code = $request->request->get('code');
        $invRepository = $this->getDoctrine()->getRepository('AppBundle:Invitation');
        $invitation = $invRepository->findOneBy(array('code' => $code));

        if (!$invitation) {
            return [];
        } else {
            return ['invitation' => $invitation];
        }
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
