<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Event;
use App\Entity\State;
use App\Form\DeleteEventFormType;
use App\Form\EventFormType;
use App\Form\IndexFormType;
use App\Repository\EventRepository;
use App\Repository\StateRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class EventController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function eventList(EntityManagerInterface $em, Request $request) {
        if ($request->query->get('page')) {
            $page = $request->query->get('page');
        } else {
            $page = 1;
        }

        if ($request->query->get('index_form')) {
            $index_form = $request->query->get('index_form');
        } else {
            $index_form = "";
        }

        if ($request->query->get('itemPerPage')) {
            $itemPerPage = $request->query->get('itemPerPage');
        } else {
            $itemPerPage = 10;
        }

        if ($index_form != "") {
            $listFilter = array(
                'campus' => $em->getRepository(Campus::class)->find($index_form['campus']),
                'search' => $index_form['search'],
                'user' => $listFilter['user'] = $this->getUser()->getId(),
            );
            if ($index_form['first_date'] != "") {
                $listFilter['first_date'] = new DateTime($index_form['first_date']);
            } else {
                $listFilter['first_date'] = new DateTime();
            }

            if ($index_form['second_date'] != "") {
                $listFilter['second_date'] = new DateTime($index_form['second_date']);
            } else {
                $listFilter['second_date'] = "";
            }

            if (array_key_exists("isPlanner", $index_form) && ($index_form['isPlanner'])) {
                $listFilter['isPlanner'] = true;
            } else {
                $listFilter['isPlanner'] = false;
            }

            if (array_key_exists("isParticipating", $index_form) && ($index_form['isParticipating'])) {
                $listFilter['isParticipating'] = true;
            } else {
                $listFilter['isParticipating'] = false;
            }

            if (array_key_exists("isNotParticipating", $index_form) && ($index_form['isNotParticipating'])) {
                $listFilter['isNotParticipating'] = true;
            } else {
                $listFilter['isNotParticipating'] = false;
            }

            if (array_key_exists("isPassed", $index_form) && ($index_form['isPassed'])) {
                $listFilter['isPassed'] = true;
            } else {
                $listFilter['isPassed'] = false;
            }
        } else {
            $listFilter = array(
                'campus' => $em->getRepository(Campus::class)->find($this->getUser()->getCampus()),
                'search' => "",
            );
            $listFilter['first_date'] = new DateTime();
            $listFilter['second_date'] = "";
            $listFilter['isPlanner'] = true;
            $listFilter['isParticipating'] = true;
            $listFilter['isNotParticipating'] = true;
            $listFilter['isPassed'] = false;
            $listFilter['user'] = $this->getUser()->getId();
        }

        $list = $em->getRepository(Event::class)->findByPageFilter($page, $itemPerPage, $listFilter);
        $listAll = $em->getRepository(Event::class)->findFilter($listFilter);

        $numberOfPage = count($listAll) / $itemPerPage;

        if ((int)$numberOfPage != $numberOfPage) {
            $numberOfPage++;
        }

        $form = $this->createForm(IndexFormType::class);
        $form->get('campus')->setData($listFilter['campus']);
        $form->get('search')->setData($listFilter['search']);
        $form->get('first_date')->setData($listFilter['first_date']);
        if($listFilter['second_date']!=""){
            $form->get('second_date')->setData($listFilter['second_date']);
        }
        $form->get('isPlanner')->setData($listFilter['isPlanner']);
        $form->get('isParticipating')->setData($listFilter['isParticipating']);
        $form->get('isNotParticipating')->setData($listFilter['isNotParticipating']);
        $form->get('isPassed')->setData($listFilter['isPassed']);


        $form->handleRequest($request);
        return $this->render('event/index.html.twig', [
            'list' => $list,
            'numberOfPage' => (int)$numberOfPage,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/createevent/{id?}", name="event_create")
     * @param EntityManagerInterface $em
     * @param EventRepository $er
     * @param StateRepository $sr
     * @param Request $request
     * @param int|null $id
     * @return Response
     */
    public function createEvent(EntityManagerInterface $em, EventRepository $er, StateRepository $sr, Request $request, int $id = null) {
        if($id != null) {
            $event = $er->find($id);
            $update = true;
        } else {
            $event = new Event();
            $update = false;
        }
        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $planner = $this->getUser();
            $state = $sr->findOneBy(['label'=>State::STATE_CREATING]);
            $event->setState($state);
            $event->setPlanner($planner);
            $event->addParticipant($planner);
            $event->setCampus($planner->getCampus());
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('event_detail', ['id' => $event->getId()]);
        }
        return $this->render('event/newevent.html.twig', [
            'form' => $form->createView(),
            'update' => $update,
            'event' => $event
        ]);
    }

    /**
     * @Route("/cancelevent/{id?}", name="cancel_create")
     * @param EntityManagerInterface $em
     * @param EventRepository $er
     * @param StateRepository $sr
     * @param Request $request
     * @param int|null $id
     * @return Response
     */
    public function cancelEvent(EntityManagerInterface $em, EventRepository $er, StateRepository $sr, Request $request, int $id = null) {
        if($id != null) {
            $event = $er->find($id);
        }
        if($event->getPlanner() == $this->getUser()) {
            if(in_array($event->getState()->getLabel(), array(State::STATE_CREATING, State::STATE_OPENED, State::STATE_CLOSED))) {
                $event->setState(State::STATE_CANCELLED);
            }
        }
        return $this->render('event/detailevent.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/deleteevent/{id}", name="event_delete", requirements={"id"="\d+"})
     * @param EntityManagerInterface $em
     * @param EventRepository $er
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function deleteEvent(EntityManagerInterface $em, EventRepository $er, Request $request, int $id) {
        $event = $er->find($id);
        if($event->getState()->getLabel() == State::STATE_CREATING) {
            if($event->getPlanner() == $this->getUser()) {
                $em->remove($event);
                $em->flush();
            }
        }
        return $this->render('event/index.html.twig');
    }

    /**
     * @Route("/publishevent/{id}", name="event_publish", requirements={"id"="\d+"})
     * @param EntityManagerInterface $em
     * @param EventRepository $er
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function publishEvent(EntityManagerInterface $em, StateRepository $sr, EventRepository $er, Request $request, int $id) {
        $event = $er->find($id);
        if($event->getState()->getLabel() == State::STATE_CREATING) {
            if($event->getPlanner() == $this->getUser()) {
                $state = $sr->findOneBy(['label'=>State::STATE_OPENED]);
                $event->setState($state);
                $em->persist($event);
                $em->flush();
            }
        }
        return $this->render('event/detailevent.html.twig', [
            'event' => $event
        ]);
    }

    /**
     * @Route("/event/{id?}", name="event_detail", requirements={"id"="\d+"})
     * @param EntityManagerInterface $em
     * @param StateRepository $sr
     * @param EventRepository $er
     * @param Request $request
     * @param int|null $id
     * @return Response
     */
    public function eventDetail(EntityManagerInterface $em, StateRepository $sr, EventRepository $er, Request $request, int $id = null) {
        $event = $er->find($id);
        if($event->getState()->getLabel() == State::STATE_OPENED) {
            $form = $this->createForm(DeleteEventFormType::class, $event);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($event->getPlanner() == $this->getUser()) {
                    if ($event->getState()->getLabel() != State::STATE_PENDING) {
                        $cancelled = $sr->findOneBy(['label' => State::STATE_CANCELLED]);
                        $event->setState($cancelled);
                        $reason = $form->get('reason')->getData();
                        $event->setReason($reason);

                        $em->persist($event);
                        $em->flush();
                    }
                    return $this->redirectToRoute('event_detail', ['id'=>$event->getId()]);
                }
            }
            return $this->render('event/detailevent.html.twig', [
                'event' => $event,
                'form' => $form->createView()
            ]);
        }
        return $this->render('event/detailevent.html.twig', [
            'event'=>$event
        ]);
    }

    /**
     * @Route("/subscribe/{id}", name="event_subscribe")
     * @param EntityManagerInterface $em
     * @param StateRepository $sr
     * @param EventRepository $er
     * @param Request $request
     * @param int|null $id
     * @return Response
     * @throws Exception
     */
    public function subscribe(EntityManagerInterface $em, StateRepository $sr, EventRepository $er, Request $request, int $id = null) {
        $event = $er->find($id);
        $now = new DateTime();
        if($event->getLastInscriptionTime() >= $now) {
            if($event->getState()->getLabel() == State::STATE_OPENED) {
                if ($event->getParticipants()->count() < $event->getPlaces()) {
                    $event->addParticipant($this->getUser());
                    if ($event->getParticipants()->count() == $event->getPlaces()) {
                        $state = $sr->findOneBy(['label' => State::STATE_CLOSED]);
                        $event->setState($state);
                    }
                }
            }
        }
        $em->persist($event);
        $em->flush();
        return $this->redirect($this->generateUrl('index'));
    }

    /**
     * @Route("/unscribe/{id}", name="event_unscribe")
     * @param EventRepository $er
     * @param StateRepository $sr
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param int|null $id
     * @return Response
     * @throws Exception
     */
    public function unscribe(EventRepository $er, StateRepository $sr, EntityManagerInterface $em, Request $request, int $id = null) {
        $event = $er->find($id);

        if($event->getState()->getLabel() == State::STATE_OPENED) {
            $event->removeParticipant($this->getUser());

        } else if($event->getState()->getLabel() == State::STATE_CLOSED) {
            $now = new DateTime();
            $event->removeParticipant($this->getUser());

            if($event->getLastInscriptionTime() >= $now) {
                $state = $sr->findOneBy(['label'=>State::STATE_OPENED]);
                $event->setState($state);
            }
        }
        $em->persist($event);
        $em->flush();
        return $this->redirect($this->generateUrl('index'));
    }
}
