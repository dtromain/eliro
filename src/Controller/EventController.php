<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event")
     */
    public function listEvents(EntityManagerInterface $em)
    {
        $list = $em->getRepository(Event::class)->findAllByPage(1, 10);

        return $this->render('event/index.html.twig', [
            'list' => $list
        ]);
    }
}
