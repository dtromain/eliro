<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CampusFixtures extends Fixture
{
    public const CAMPUS_NANTES_REFERENCE = 'campus-nantes';
    public const CAMPUS_QUIMPER_REFERENCE = 'campus-quimper';
    public const CAMPUS_RENNES_REFERENCE = 'campus-rennes';
    public const CAMPUS_NIORT_REFERENCE = 'campus-niort';

    public function load(ObjectManager $manager)
    {
        $nantes = new Campus();
        $nantes->setName("Nantes");
        $this->addReference(self::CAMPUS_NANTES_REFERENCE, $nantes);

        $quimper = new Campus();
        $quimper->setName("Quimper");
        $this->addReference(self::CAMPUS_QUIMPER_REFERENCE, $quimper);

        $rennes = new Campus();
        $rennes->setName("Rennes");
        $this->addReference(self::CAMPUS_RENNES_REFERENCE, $rennes);

        $niort = new Campus();
        $niort->setName("Niort");
        $this->addReference(self::CAMPUS_NIORT_REFERENCE, $niort);

        $manager->persist($nantes);
        $manager->persist($quimper);
        $manager->persist($rennes);
        $manager->persist($niort);
        $manager->flush();
    }
}
