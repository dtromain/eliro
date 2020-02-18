<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CampusFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $nantes = new Campus("Nantes");
        $quimper = new Campus("Quimper");
        $rennes = new Campus("Rennes");
        $niort = new Campus("Niort");
        $manager->persist($nantes);
        $manager->persist($quimper);
        $manager->persist($rennes);
        $manager->persist($niort);
        $manager->flush();
    }



}
