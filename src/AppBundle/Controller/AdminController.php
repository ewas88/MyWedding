<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Invitation;
use AppBundle\Entity\Guest;
use AppBundle\Entity\Present;
use AppBundle\Entity\ToDoList;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Symfony\Component\Validator\Constraints\DateTime;

use Symfony\Component\HttpFoundation\Request;

//use Symfony\Component\HttpFoundation\Response;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
//use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin")
 * @Security("is_granted('ROLE_USER')")
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

        $datetime1 = strtotime(date('Y-m-d'));
        $datetime2 = strtotime($wedding->getWeddingDate()->format('Y-m-d'));
        $days = ($datetime2 - $datetime1) / 86400;

        return ['days' => $days, 'wedding' => $wedding];
    }


    /**
     * @Route("/invitations")
     * @Template("AppBundle:Admin:invitation.html.twig")
     */
    public function invitationAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $invitation = new Invitation();
        $invitation->setCode();
        $em->persist($invitation);
        $em->flush();

        $guests = $request->request->get('guest_list');

        if (!$guests == NULL) {

            foreach ($guests as $guest) {
                $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
                $guest2 = $guestRepository->find($guest);
                $guest2->setInvitation($invitation);
                $em->persist($guest2);
                $em->flush();
            }
        }

        $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
        $guests = $guestRepository->findBy(array('invitation' => NULL));

        $inviteRepository = $this->getDoctrine()->getRepository('AppBundle:Invitation');
        $invitations = $inviteRepository->findBy(array(), array('id' => 'ASC')
        );

        return ['guests' => $guests, 'invitations' => $invitations];
    }

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
            $invitation = NULL;
        } else {

            $inviteRepository = $this->getDoctrine()->getRepository('AppBundle:Invitation');
            $invitation = $inviteRepository->find($invite);
        }

        if ($isSingle == 2) {
            $isSingle = NULL;
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
        $present->setInvitation(NULL);

        $em->persist($present);
        $em->flush();

        $presentsRepository = $this->getDoctrine()->getRepository('AppBundle:Present');
        $presents = $presentsRepository->findBy(array(), array('name' => 'ASC'));

        return ['presents' => $presents];
    }

    /**
     * @Route("/budget")
     * @Template("AppBundle:Admin:cost.html.twig")
     * @Method("GET")
     */
    public function budgetAction()
    {
        return [];
    }

    /**
     * @Route("/list")
     * @Template("AppBundle:Admin:list.html.twig")
     * @Method("GET")
     */
    public function listAction()
    {
        $tasksRepository = $this->getDoctrine()->getRepository('AppBundle:ToDo');
        $tasks = $tasksRepository->findAll();

        return ['tasks' => $tasks];
    }

    /**
     * @Route("/list")
     * @Template("AppBundle:Admin:list.html.twig")
     * @Method("POST")
     */
    public function newListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $todo = $request->request->get('task');
        $deadline = $request->request->get('deadline');

        $task = new ToDo();
        $task->setTask($todo);
        if ($deadline != "") {
            $task->setDeadline(new \DateTime($deadline));
        }
        $task->setIsDone(0);
        $task->setUser($this->getUser());

        $em->persist($task);
        $em->flush();

        $tasksRepository = $this->getDoctrine()->getRepository('AppBundle:ToDo');
        $tasks = $tasksRepository->findAll();

        return ['tasks' => $tasks];
    }
}
