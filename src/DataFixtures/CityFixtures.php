<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CityFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $nantes = new City();
        $nantes->setName("Nantes");
        $nantes->setPostcode("44000");

        $quimper = new City();
        $quimper->setName("Quimper");
        $quimper->setPostcode("29000");

        $rennes = new City();
        $rennes->setName("Rennes");
        $rennes->setPostcode("35000");

        $niort = new City();
        $niort->setName("Niort");
        $niort->setPostcode("79000");

        $manager->persist($nantes);
        $manager->persist($quimper);
        $manager->persist($rennes);
        $manager->persist($niort);

        $manager->flush();
    }
}
