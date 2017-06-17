<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Invitation;
use AppBundle\Entity\Guest;
use AppBundle\Entity\Present;
use AppBundle\Entity\ToDoList;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
     * @Route("/info")
     * @Template("AppBundle:Admin:info.html.twig")
     */
    public function infoSiteAction()
    {
        $weddingRepository = $this->getDoctrine()->getRepository('AppBundle:WeddingInfo');
        $wedding = $weddingRepository->find(1);
        return ['wedding' => $wedding];
    }

    /**
     * @Route("/invitations")
     * @Template("AppBundle:Admin:invitation.html.twig")
     * @Method("GET")
     */
    public function invitationAction()
    {
        $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
        $guests = $guestRepository->findBy(array('invitation' => NULL));
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
        $em->remove($invite);
        $em->flush();

        $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
        $guests = $guestRepository->findBy(array('invitation' => NULL));
        $people = $guestRepository->findAll();

        $inviteRepository = $this->getDoctrine()->getRepository('AppBundle:Invitation');
        $invitations = $inviteRepository->findBy(array(), array('id' => 'ASC')
        );

        return ['guests' => $guests, 'invitations' => $invitations, 'people' => $people];
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
     * @Route("/guests/delete/{id}", name="delete_guest")
     * @Template("AppBundle:Admin:guest.html.twig")
     */
    public function deleteGuestAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $guestRepository = $this->getDoctrine()->getRepository('AppBundle:Guest');
        $guest = $guestRepository->find($id);
        $em->remove($guest);
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
     * @Route("/presents/delete/{id}", name="delete_present")
     * @Template("AppBundle:Admin:present.html.twig")
     */
    public function deletePresentAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $presentRepository = $this->getDoctrine()->getRepository('AppBundle:Present');
        $present = $presentRepository->find($id);
        $em->remove($present);
        $em->flush();

        $presentsRepository = $this->getDoctrine()->getRepository('AppBundle:Present');
        $presents = $presentsRepository->findBy(array(), array('name' => 'ASC'));

        return ['presents' => $presents];
    }


    /**
     * @Route("/list")
     * @Template("AppBundle:Admin:list.html.twig")
     * @Method("GET")
     */
    public function listAction()
    {
        $tasksRepository = $this->getDoctrine()->getRepository('AppBundle:ToDoList');
        $tasks = $tasksRepository->findBy(array(), array('isDone' => 'ASC', 'deadline' => 'ASC'));

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

        $task = new ToDoList();
        $task->setTask($todo);

        if ($deadline != "") {
            $task->setDeadline(new \DateTime($deadline));
        } else {
            $task->setDeadline(new \DateTime('now'));
        }

        $task->setIsDone(0);

        $em->persist($task);
        $em->flush();

        $tasksRepository = $this->getDoctrine()->getRepository('AppBundle:ToDoList');
        $tasks = $tasksRepository->findBy(array(), array('isDone' => 'ASC', 'deadline' => 'ASC'));

        return ['tasks' => $tasks];
    }

    /**
     * @Route("/list/done/{id}", name="done_list")
     * @Template("AppBundle:Admin:list.html.twig")
     */
    public function doneListAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $tasksRepository = $this->getDoctrine()->getRepository('AppBundle:ToDoList');
        $task = $tasksRepository->find($id);
        $task->setIsDone(1);
        $em->persist($task);
        $em->flush();

        $tasksRepository = $this->getDoctrine()->getRepository('AppBundle:ToDoList');
        $tasks = $tasksRepository->findBy(array(), array('isDone' => 'ASC', 'deadline' => 'ASC'));

        return ['tasks' => $tasks];

    }

    /**
     * @Route("/list/delete/{id}", name="delete_list")
     * @Template("AppBundle:Admin:list.html.twig")
     */
    public function deleteListAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $tasksRepository = $this->getDoctrine()->getRepository('AppBundle:ToDoList');
        $task = $tasksRepository->find($id);
        $em->remove($task);
        $em->flush();

        $tasksRepository = $this->getDoctrine()->getRepository('AppBundle:ToDoList');
        $tasks = $tasksRepository->findBy(array(), array('isDone' => 'ASC', 'deadline' => 'ASC'));

        return ['tasks' => $tasks];
    }
}
