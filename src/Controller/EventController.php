<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event")
     */
    public function listEvents(EntityManagerInterface $em,int $page =1,int $itemPerPage =10)
    {
        $page = $_GET["page"];

        $list = $em->getRepository(Event::class)->findAllByPage($page, $itemPerPage);

        return $this->render('event/index.html.twig', [
            'list' => $list
        ]);
    }

    /**
     * @Route("/newevent", name="newevent")
     * @param Request $request
     * @return Response
     */
    public function createEvent(Request $request) {

        $event = $request->query->get('event');

        if(!$event) {
            $event = new Event();
        }

        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->render('event/newevent.html.twig', [
                'form' => $form->createView()
            ]);
        }

        return $this->render('event/newevent.html.twig', [
            'form'=>$form->createView(),
        ]);
    }
}
