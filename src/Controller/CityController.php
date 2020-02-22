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

            return $this->redirect($this->generateUrl('cities'));
        }

        return $this->render('city/index.html.twig', [
            'form' => $form->createView(),
            'cities'=> $cities
        ]);
    }

    /**
     * @Route("/deletecity", name="deletecity")
     * @param Request $request
     * @return Response
     */
    public function deleteCity(Request $request)
    {
        $id = $request->query->get('id');
        $em = $this->getDoctrine()->getManager();
        $city = $em->getRepository(City::class)->findOneById($id);
        $em->remove($city);
        $em->flush();
        return $this->redirect($this->generateUrl('cities'));
    }

    /**
     * @Route("/modifycity", name="modifycity")
     * @param Request $request
     * @return Response
     */
    public function modifyCity(Request $request)
    {
        $id = $request->query->get('id');
        $em = $this->getDoctrine()->getManager();
        $city = $em->getRepository(City::class)->findOneById($id);
        return $this->redirect($this->generateUrl('cities', [
            'city' => $city
        ]));
    }
}
