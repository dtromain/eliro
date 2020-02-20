<?php

namespace App\DataFixtures;

use App\Entity\Location;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class LocationFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $location1 = new Location();
        $location1->setName($faker->name);
        $location1->setStreet($faker->streetAddress);
        $location1->setLatitude($faker->latitude);
        $location1->setLongitude($faker->longitude);
        $location1->setCity($this->getReference(CityFixtures::CITY_NANTES_REFERENCE));

        $location2 = new Location();
        $location2->setName($faker->name);
        $location2->setStreet($faker->streetAddress);
        $location2->setLatitude($faker->latitude);
        $location2->setLongitude($faker->longitude);
        $location2->setCity($this->getReference(CityFixtures::CITY_NANTES_REFERENCE));

        $location3 = new Location();
        $location3->setName($faker->name);
        $location3->setStreet($faker->streetAddress);
        $location3->setLatitude($faker->latitude);
        $location3->setLongitude($faker->longitude);
        $location3->setCity($this->getReference(CityFixtures::CITY_NIORT_REFERENCE));

        $location4 = new Location();
        $location4->setName($faker->name);
        $location4->setStreet($faker->streetAddress);
        $location4->setLatitude($faker->latitude);
        $location4->setLongitude($faker->longitude);
        $location4->setCity($this->getReference(CityFixtures::CITY_NIORT_REFERENCE));

        $location5 = new Location();
        $location5->setName($faker->name);
        $location5->setStreet($faker->streetAddress);
        $location5->setLatitude($faker->latitude);
        $location5->setLongitude($faker->longitude);
        $location5->setCity($this->getReference(CityFixtures::CITY_RENNES_REFERENCE));

        $location6 = new Location();
        $location6->setName($faker->name);
        $location6->setStreet($faker->streetAddress);
        $location6->setLatitude($faker->latitude);
        $location6->setLongitude($faker->longitude);
        $location6->setCity($this->getReference(CityFixtures::CITY_QUIMPER_REFERENCE));

        $manager->persist($location1);
        $manager->persist($location2);
        $manager->persist($location3);
        $manager->persist($location4);
        $manager->persist($location5);
        $manager->persist($location6);

        $manager->flush();
    }
}
