<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ToDoList;
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
class ToDoListController extends Controller
{
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

        if ($task) {
            $em->remove($task);
            $em->flush();
        }

        $tasksRepository = $this->getDoctrine()->getRepository('AppBundle:ToDoList');
        $tasks = $tasksRepository->findBy(array(), array('isDone' => 'ASC', 'deadline' => 'ASC'));

        return ['tasks' => $tasks];
    }
}
