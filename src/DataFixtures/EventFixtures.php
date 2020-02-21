<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 50; $i++) {
            $event = new Event();

            try {
                $randomDate = new \DateTime(sprintf('-%d days', rand(-30, 30)));
            } catch (\Exception $e) {
            }
            $event->setStarttime($randomDate);

            $event->setName('Soirée n°'.$i.' du '.$randomDate->format('Y-m-d H:i:s'));
            $lastinscriptiondate = clone $randomDate;
            $lastinscriptiondate->modify('-1 day');
            $event->setDuration(mt_rand(1, 30)*10);

            $event->setLastInscriptionTime($lastinscriptiondate);

            $event->setInformation('');

            switch (rand(0,3)) {
                case 0:
                    $event->setState($this->getReference(StateFixtures::STATE_CREATING_REFERENCE));
                    break;
                case 1:
                    $event->setState($this->getReference(StateFixtures::STATE_OPEN_REFERENCE));
                    break;
                case 2:
                    $event->setState($this->getReference(StateFixtures::STATE_CLOSE_REFERENCE));
                    break;
                case 3:
                    $event->setState($this->getReference(StateFixtures::STATE_ONGOING_REFERENCE));
                    break;

            }

            switch (rand(0,3)) {
                case 0:
                    $event->setCampus($this->getReference(CampusFixtures::CAMPUS_NANTES_REFERENCE));
                    break;
                case 1:
                    $event->setCampus($this->getReference(CampusFixtures::CAMPUS_QUIMPER_REFERENCE));
                    break;
                case 2:
                    $event->setCampus($this->getReference(CampusFixtures::CAMPUS_RENNES_REFERENCE));
                    break;
                case 3:
                    $event->setCampus($this->getReference(CampusFixtures::CAMPUS_NIORT_REFERENCE));
                    break;

            }

            $manager->persist($event);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            StateFixtures::class,
        );
    }
}
