<?php

namespace App\DataFixtures;

use App\Entity\Location;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class LocationFixtures extends Fixture
{

    public const LOCATION_NUMBER = 50;

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($i = 0 ; $i < self::LOCATION_NUMBER ; $i++) {
            $location = new Location();
            $location->setName($faker->company);
            $location->setStreet($faker->streetAddress);
            $location->setLatitude($faker->latitude);
            $location->setLongitude($faker->longitude);

            switch (rand(0, 3)) {
                case 0:
                    $location->setCity($this->getReference(CityFixtures::CITY_NANTES_REFERENCE));
                    break;
                case 1:
                    $location->setCity($this->getReference(CityFixtures::CITY_QUIMPER_REFERENCE));
                    break;
                case 2:
                    $location->setCity($this->getReference(CityFixtures::CITY_RENNES_REFERENCE));
                    break;
                case 3:
                    $location->setCity($this->getReference(CityFixtures::CITY_NIORT_REFERENCE));
                    break;
            }

            $this->addReference('LOCATION_'.$i.'_REFERENCE', $location);

            $manager->persist($location);
        }

        $manager->flush();
    }
}
