<?php

namespace App\DataFixtures;

use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class EventFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 20; $i++) {
            $event = new Event();

            //Generate a timestamp using mt_rand.
            $timestamp = mt_rand(1, time());
            //Format that timestamp into a readable date string.
            $randomDate = date("d M Y", $timestamp);
            $event->setStarttime($randomDate);

            $event->setName('Soirée du '.$randomDate);

            $event->setDuration(mt_rand(10, 300));

            $event->setLastInscriptionTime($randomDate)->modify('-1 day');

            $event->setState('en creation');

            $event->setLocation('chez moi');

            $manager->persist($event);
        }

        $manager->flush();
    }
}
