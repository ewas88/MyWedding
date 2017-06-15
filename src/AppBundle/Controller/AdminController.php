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
        $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $userRepository->find($this->getUser());

        $date = $user->getWeddingDate()->format('Y-m-d');

        $datetime1 = strtotime('2017-06-12');
        $datetime2 = strtotime($date);

        $secs = $datetime2 - $datetime1;
        $days = $secs / 86400;

        return ['days' => $days];
    }

    /**
     * @Route("/invitations")
     * @Template("AppBundle:Admin:invitation.html.twig")
     * @Method("GET")
     */
    public function invitationAction()
    {
        $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
        $guests = $guestRepository->getByInvite();

        $inviteRepository = $this->getDoctrine()->getRepository('AppBundle:Invitation');
        $invitations = $inviteRepository->sortInvites();

        return ['guests' => $guests, 'invitations' => $invitations];
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

        $newInv = $invitation->getId();
        $guests = $request->request->get('guest_list');

        if (!$guests == NULL) {

            foreach ($guests as $guest) {
                $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
                $guest2 = $guestRepository->find($guest);
                $guest2->setInviteId($newInv);
                $em->persist($guest2);
                $em->flush();
            }
        }

        $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
        $guests = $guestRepository->getByInvite();

        $inviteRepository = $this->getDoctrine()->getRepository('AppBundle:Invitation');
        $invitations = $inviteRepository->sortInvites();

        return ['guests' => $guests, 'invitations' => $invitations];
    }

    /**
     * @Route("/guests")
     * @Template("AppBundle:Admin:guest.html.twig")
     * @Method("GET")
     */
    public function guestAction()
    {
        $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
        $guests = $guestRepository->sortGuests($this->getUser()->getId());

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
        $relation = $request->request->get('relation');
        $isSingle = $request->request->get('is_single');
        $invite = $request->request->get('invite');

        $inviteRepository = $this->getDoctrine()->getRepository('AppBundle:Invitation');
        $invitation = $inviteRepository->find($invite);

        $guest = new Guest();
        $guest->setName($name);
        $guest->setSurname($surname);
        $guest->setEmail($email);
        $guest->setRelation($relation);
        $guest->setIsSingle($isSingle);
        $guest->setInvitation($invitation);
        $guest->setUser($this->getUser());

        $em->persist($guest);
        $em->flush();

        $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
        $guests = $guestRepository->sortGuests();

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
        $presents = $presentsRepository->sortPresents();

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
        $present->setIsTaken(0);

        $em->persist($present);
        $em->flush();

        $presentsRepository = $this->getDoctrine()->getRepository('AppBundle:Present');
        $presents = $presentsRepository->sortPresents();

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
