<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CampusController extends AbstractController
{
    /**
     * @Route("/campus", name="campus")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {

        $campus = $request->query->get('campus');

        if(!$campus) {
            $campus = new Campus();
        }

        $form = $this->createForm(CampusFormType::class, $campus);
        $form->handleRequest($request);

        $campuses = $this->getDoctrine()->getRepository(Campus::class)->findAll();

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($campus);
            $entityManager->flush();

            return $this->render('campus/index.html.twig', [
                'form' => $form->createView(),
                'campuses'=> $campuses
            ]);
        }

        return $this->render('campus/index.html.twig', [
            'controller_name' => 'CampusController',
            'form' => $form->createView(),
            'campuses'=>$campuses
        ]);
    }
}
