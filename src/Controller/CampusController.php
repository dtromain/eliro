<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusFormType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CampusController extends AbstractController
{
    /**
     * @Route("/campus", name="campus")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param CampusRepository $cr
     * @return RedirectResponse|Response
     */
    public function index(Request $request, EntityManagerInterface $em, CampusRepository $cr)
    {
        $campus_name = $request->query->get('campus');
        $campus = $cr->findOneBy(['name'=>$campus_name]);

        if(!$campus) {
            $campus = new Campus();
        }

        $form = $this->createForm(CampusFormType::class, $campus);
        $form->handleRequest($request);
        $campuses = $cr->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($campus);
            $em->flush() ;
            return $this->redirect($this->generateUrl('campus'));
        }

        return $this->render('campus/index.html.twig', [
            'controller_name' => 'CampusController',
            'form' => $form->createView(),
            'campuses'=>$campuses
        ]);
    }

    /**
     * @Route("/deletecampus", name="deletecampus")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param CampusRepository $cr
     * @return Response
     */
    public function deleteCampus(Request $request, EntityManagerInterface $em, CampusRepository $cr)
    {
        $id = $request->query->get('id');
        $campus = $cr->findOneById($id);
        $em->remove($campus);
        $em->flush();
        return $this->redirect($this->generateUrl('campus'));
    }

    /**
     * @Route("/modifycampus", name="modifycampus")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param CampusRepository $cr
     * @return Response
     */
    public function modifyCampus(Request $request, EntityManagerInterface $em, CampusRepository $cr)
    {
        $id = $request->query->get('id');
        $campus = $cr->findOneById($id);
        $em->persist($campus);
        $em->flush();
        return $this->redirect($this->generateUrl('campus', [
            'campus' => $campus
        ]));
    }
}
