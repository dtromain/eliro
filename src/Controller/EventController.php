<?php

namespace App\Controller;

use App\DataFixtures\StateFixtures;
use App\Entity\Event;
use App\Entity\State;
use App\Form\EventFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class EventController extends AbstractController
{
    /**
     * @Route("/", name="event")
     */
    public function listEvents(EntityManagerInterface $em, Request $request)
    {
        if($request->query->get('page')) {
            $page = $request->query->get('page');
        } else {
            $page = 1;
        }

        if($request->query->get('itemPerPage')) {
            $itemPerPage = $request->query->get('itemPerPage');
        } else {
            $itemPerPage = 10;
        }

        $list = $em->getRepository(Event::class)->findNotHappendByPage($page, $itemPerPage);
        $listAll =$em->getRepository(Event::class)->findNotHappend();
        $numberOfPage = count($listAll)/$itemPerPage;
        if ((int)$numberOfPage!=$numberOfPage){
            $numberOfPage++;
        }
        return $this->render('event/index.html.twig', [
            'list' => $list,
            'numberOfPage' => (int)$numberOfPage
        ]);
    }

    /**
     * @Route("/newevent", name="newevent")
     * @param Request $request
     * @return Response
     */
    public function createEvent(EntityManagerInterface $em, Request $request) {

        $event = $request->query->get('event');

        if(!$event) {
            $event = new Event();
        }
        $creating =$em->getRepository(State::class)->findOneBy(['label' => 'creating']);
        $event->setState($creating);
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
