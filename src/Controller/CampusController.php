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
     * @Route("/campus/{id?}", name="campus_list", requirements={"id"="\d+"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param CampusRepository $cr
     * @param int $id
     * @return RedirectResponse|Response
     */
    public function campusList(Request $request, EntityManagerInterface $em, CampusRepository $cr, int $id = null) {
        $campusList = $cr->findAll();
        if ($id != null) {
            $campus = $cr->find($id);
        } else {
            $campus = new Campus();
        }
        $form = $this->createForm(CampusFormType::class, $campus);
        $form->handleRequest($request);

        // Remove the current edited campus from the campus list
        $i = 0;
        foreach ($campusList as $campus_iter) {
            if($campus_iter === $campus) {
                unset($campusList[$i]);
            }
            $i++;
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($campus);
            $em->flush();
            return $this->redirect($this->generateUrl('campus_list'));
        }
        return $this->render('campus/index.html.twig', [
            'form' => $form->createView(),
            'campusList' => $campusList
        ]);
    }

    /**
     * @Route("/deletecampus", name="campus_delete")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param CampusRepository $cr
     * @return Response
     */
    public function campusDelete(Request $request, EntityManagerInterface $em, CampusRepository $cr) {
        $id = $request->query->get('id');
        $campus = $cr->find($id);
        $em->remove($campus);
        $em->flush();
        return $this->redirect($this->generateUrl('campus_list'));
    }
}
