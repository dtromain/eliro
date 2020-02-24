<?php

namespace App\Controller;

use App\DataFixtures\StateFixtures;
use App\Entity\Event;
use App\Entity\Participant;
use App\Entity\State;
use App\Form\EventFormType;
use App\Form\IndexFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class EventController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
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

        $form = $this->createForm(IndexFormType::class);
        $form->handleRequest($request);

        return $this->render('event/index.html.twig', [
            'list' => $list,
            'numberOfPage' => (int)$numberOfPage,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/newevent", name="newevent")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function createEvent(EntityManagerInterface $em, Request $request) {

        $event = $request->query->get('event');

        if(!$event) {
            $event = new Event();
        }
        $creating =$em->getRepository(State::class)->findOneBy(['label' => 'En création']);
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

    /**
     * @Route("/detailevent", name="detailevent")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function detailEvent(EntityManagerInterface $em, Request $request) {

        $id = $request->query->get('id');

        $em = $this->getDoctrine()->getRepository(Event::class);
        $event = $em->find($id);

        return $this->render('event/detailevent.html.twig', ['event'=>$event]);
    }

    /**
     * @Route("/subscribe", name="subscribe")
     * @param Request $request
     * @return Response
     */
    public function subscribe(Request $request) {

        $id = $request->query->get('id');
        $em = $this->getDoctrine()->getManager();

        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
        $user = $this->getUser();

        if($event->getStatus()->getLabel('Ouvert')) {
            if ($event->getParticipants()->count() <= $event->getPlaces()) {
                $event->addParticipant($user);
            }
        }

        $em->persist($event);
        $em->flush();

        return $this->redirect($this->generateUrl('index'));
    }

    /**
     * @Route("/unscribe", name="unscribe")
     * @param Request $request
     * @return Response
     */
    public function unscribe(Request $request) {

        $id = $request->query->get('id');
        $em = $this->getDoctrine()->getManager();

        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
        $user = $this->getUser();

        if($event->getStatus()->getLabel('Ouvert')) {
            $event->removeParticipant($user);
        }

        $em->persist($event);
        $em->flush();

        return $this->redirect($this->generateUrl('index'));
    }

}
