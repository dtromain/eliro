<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class EventFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 5; $i++) {
            $event = new Event();

            try {
                $randomDate = new \DateTime(sprintf('-%d days', rand(-30, 30)));
            } catch (\Exception $e) {
            }
            $event->setStarttime($randomDate);

            $event->setName('Soirée n°'.$i.' du '.$randomDate->format('Y-m-d H:i:s'));

            $event->setDuration(mt_rand(1, 30)*10);

            $event->setLastInscriptionTime($randomDate->modify('-1 day'));

            $event->setInformation('');

            $manager->persist($event);
        }

        $manager->flush();
    }
}
