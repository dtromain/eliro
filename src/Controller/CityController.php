<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityFormType;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{
    /**
     * @Route("/city", name="city_list")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param CityRepository $cr
     * @return Response
     */
    public function city_list(Request $request, EntityManagerInterface $em, CityRepository $cr)
    {
        $id = $request->query->get('id');

        if($id != null) {
            $city = $cr->find($id);
        } else {
            $city = new City();
        }

        $form = $this->createForm(CityFormType::class, $city);
        $form->handleRequest($request);
        $cities = $cr->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($city);
            $em->flush();
            return $this->redirect($this->generateUrl('city_list'));
        }

        return $this->render('city/index.html.twig', [
            'form' => $form->createView(),
            'cities'=> $cities
        ]);
    }

    /**
     * @Route("/deletecity", name="city_delete")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param CityRepository $cr
     * @return Response
     */
    public function deleteCity(Request $request, EntityManagerInterface $em, CityRepository $cr)
    {
        $id = $request->query->get('id');
        $city = $cr->findOneById($id);
        $em->remove($city);
        $em->flush();
        return $this->redirect($this->generateUrl('city_list'));
    }
}
