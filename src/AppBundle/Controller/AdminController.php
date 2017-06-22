<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin")
 * @Security("has_role('ROLE_USER')")
 */
class AdminController extends Controller
{
    /**
     * @Route("/")
     * @Template("AppBundle:Admin:base.html.twig")
     */
    public function adminSiteAction()
    {
        $weddingRepository = $this->getDoctrine()->getRepository('AppBundle:WeddingInfo');
        $wedding = $weddingRepository->find(1);

        $today = strtotime(date('Y-m-d'));
        $weddingDate = strtotime($wedding->getWeddingDate()->format('Y-m-d'));
        $daysUntilWedding = ($weddingDate - $today) / 86400;

        return ['days' => $daysUntilWedding, 'wedding' => $wedding];
    }

    /**
     * @Route("/info")
     * @Template("AppBundle:Admin:info.html.twig")
     */
    public function infoSiteAction()
    {
        $weddingRepository = $this->getDoctrine()->getRepository('AppBundle:WeddingInfo');
        $wedding = $weddingRepository->find(1);
        return ['wedding' => $wedding];
    }
}
