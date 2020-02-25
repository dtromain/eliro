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
     * @Route("/cities", name="cities")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function index(Request $request, EntityManagerInterface $em, CityRepository $cr)
    {
        $city_name = $request->query->get('city');
        $city = $cr->findOneBy(['name'=>$city_name]);

        if(!$city) {
            $city = new City();
        }

        $form = $this->createForm(CityFormType::class, $city);
        $form->handleRequest($request);
        $cities = $cr->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($city);
            $em->flush();
            return $this->redirect($this->generateUrl('cities'));
        }

        return $this->render('city/index.html.twig', [
            'form' => $form->createView(),
            'cities'=> $cities
        ]);
    }

    /**
     * @Route("/villes_delete", name="deletecity")
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
        return $this->redirect($this->generateUrl('cities'));
    }

    /**
     * @Route("/modifycity", name="modifycity")
     * @param Request $request
     * @return Response
     */
    public function modifyCity(Request $request, EntityManagerInterface $em, CityRepository $cr)
    {
        $id = $request->query->get('id');
        $city = $em->getRepository(City::class)->findOneById($id);
        $em->persist($city);
        $em->flush();
        return $this->redirect($this->generateUrl('cities', [
            'city' => $city
        ]));
    }
}
