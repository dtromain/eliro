<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityFormType;
use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{
    /**
     * @Route("/cities", name="cities")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {

        $city = $request->query->get('city');

        if(!$city) {
            $city = new City();
        }

        $form = $this->createForm(CityFormType::class, $city);
        $form->handleRequest($request);

        $cities = $this->getDoctrine()->getRepository(City::class)->findAll();

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($city);
            $entityManager->flush();

            return $this->render('city/index.html.twig', [
                'form' => $form->createView(),
                'cities'=> $cities
            ]);
        }

        return $this->render('city/index.html.twig', [
            'form' => $form->createView(),
            'cities'=> $cities
        ]);
    }
}
