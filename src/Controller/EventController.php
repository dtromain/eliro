<?php

namespace App\Controller;

use App\DataFixtures\StateFixtures;
use App\Entity\Campus;
use App\Entity\Event;
use App\Entity\Participant;
use App\Entity\State;
use App\Form\DeleteEventFormType;
use App\Form\EventFormType;
use App\Form\IndexFormType;
use App\Repository\EventRepository;
use App\Repository\StateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use function Webmozart\Assert\Assert;

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
     * @Route("/newevent", name="newevent")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function createEvent(EntityManagerInterface $em, Request $request)
    {

        $event = $request->query->get('event');

        if (!$event) {
            $event = new Event();
        }

        $creating =$em->getRepository(State::class)->findOneBy([
            'label' => 'En création'
        ]);
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
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/event", name="event")
     * @param EntityManagerInterface $em
     * @param EventRepository $er
     * @param StateRepository $sr
     * @param Request $request
     * @return Response
     */
    public function detailEvent(EntityManagerInterface $em, EventRepository $er, StateRepository $sr, Request $request) {

        $id = $request->query->get('id');
        $event = $er->find($id);

        $form = $this->createForm(DeleteEventFormType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reason = $form->get('reason')->getData();
            $state_cancelled = $sr->findOneBy(['label' => State::STATE_CANCELLED]);
            $event->setState($state_cancelled);
            $event->setReason($reason);

            $em->persist($event);
            $em->flush();

            return $this->render('event/detailevent.html.twig', [
                'event'=>$event
            ]);
        }

        return $this->render('event/detailevent.html.twig', ['event'=>$event,
            'form'=>$form
        ]);
    }

    /**
     * @Route("/deleteevent", name="deleteevent")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function deteteEvent(EntityManagerInterface $em, Request $request) {

        $id = $request->query->get('id');
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);

        $form = $this->createForm(DeleteEventFormType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($event->getPlanner() == $this->getUser()) {
                if($event->getState()->getLabel() != State::STATE_PENDING)
                {
                    $em->remove($event);
                }
            }
        }

        return $this->redirect($this->generateUrl('index'));
    }

    /**
     * @Route("/subscribe", name="subscribe")
     * @param EntityManagerInterface $em
     * @param EventRepository $er
     * @param Request $request
     * @return Response
     */
    public function subscribe(EntityManagerInterface $em, EventRepository $er, Request $request) {

        $id = $request->query->get('id');

        $event = $er->find($id);
        $user = $this->getUser();

        if($event->getState()->getLabel() == State::STATE_OPEN) {
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
     * @param EventRepository $er
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function unscribe(EventRepository $er, EntityManagerInterface $em, Request $request) {

        $id = $request->query->get('id');

        $event = $er->find($id);
        $user = $this->getUser();

        if($event->getState()->getLabel() == State::STATE_OPEN) {
            $event->removeParticipant($user);
        }

        $em->persist($event);
        $em->flush();

        return $this->redirect($this->generateUrl('index'));
    }

}
