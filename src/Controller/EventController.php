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
    public function listEvents(EntityManagerInterface $em,int $page =1,int $itemPerPage =10)
    {
        $page = $_GET["page"];

        $list = $em->getRepository(Event::class)->findAllByPage($page, $itemPerPage);

        return $this->render('event/index.html.twig', [
            'list' => $list
        ]);
    }
}
