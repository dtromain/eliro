<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CityFixtures extends Fixture
{
    public const CITY_NANTES_REFERENCE = 'city-nantes';
    public const CITY_QUIMPER_REFERENCE = 'city-quimper';
    public const CITY_RENNES_REFERENCE = 'city-rennes';
    public const CITY_NIORT_REFERENCE = 'city-niort';
    
    public function load(ObjectManager $manager)
    {
        $nantes = new City();
        $nantes->setName("Nantes");
        $nantes->setPostcode("44000");
        $this->addReference(self::CITY_NANTES_REFERENCE, $nantes);

        $quimper = new City();
        $quimper->setName("Quimper");
        $quimper->setPostcode("29000");
        $this->addReference(self::CITY_QUIMPER_REFERENCE, $quimper);

        $rennes = new City();
        $rennes->setName("Rennes");
        $rennes->setPostcode("35000");
        $this->addReference(self::CITY_RENNES_REFERENCE, $rennes);

        $niort = new City();
        $niort->setName("Niort");
        $niort->setPostcode("79000");
        $this->addReference(self::CITY_NIORT_REFERENCE, $niort);

        $manager->persist($nantes);
        $manager->persist($quimper);
        $manager->persist($rennes);
        $manager->persist($niort);

        $manager->flush();
    }
}
