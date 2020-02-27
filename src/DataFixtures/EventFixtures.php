<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Participant;
use App\Entity\State;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Exception;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 500; $i++) {
            $event = new Event();

            try {
                $randomDate = new DateTime(sprintf('-%d days', rand(-60, 60)));
            } catch (Exception $e) {
            }
            $event->setStarttime($randomDate);

            $event->setName('Soirée n°' . $i . ' du ' . $randomDate->format('Y-m-d H:i:s'));
            $lastinscriptiondate = clone $randomDate;
            $lastinscriptiondate->modify('-1 day');
            $event->setDuration(mt_rand(1, 30) * 10);

            $event->setLastInscriptionTime($lastinscriptiondate);

            $event->setInformation('');

            switch (rand(0, 6)) {
                case 0:
                    $event->setState($this->getReference(StateFixtures::STATE_CREATING_REFERENCE));
                    break;
                case 1:
                    $event->setState($this->getReference(StateFixtures::STATE_OPENED_REFERENCE));
                    break;
                case 2:
                    $event->setState($this->getReference(StateFixtures::STATE_CLOSED_REFERENCE));
                    break;
                case 3:
                    $event->setState($this->getReference(StateFixtures::STATE_PENDING_REFERENCE));
                    break;
                case 4:
                    $event->setState($this->getReference(StateFixtures::STATE_FINISHED_REFERENCE));
                    break;
                case 5:
                    $event->setState($this->getReference(StateFixtures::STATE_CANCELLED_REFERENCE));
                    break;
                case 6:
                    $event->setState($this->getReference(StateFixtures::STATE_HISTORISED_REFERENCE));
                    break;
            }

            switch (rand(0, 3)) {
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

            switch (rand(0, 5)) {
                case 0:
                    $event->setLocation($this->getReference(LocationFixtures::LOCATION_1_REFERENCE));
                    break;
                case 1:
                    $event->setLocation($this->getReference(LocationFixtures::LOCATION_2_REFERENCE));
                    break;
                case 2:
                    $event->setLocation($this->getReference(LocationFixtures::LOCATION_3_REFERENCE));
                    break;
                case 3:
                    $event->setLocation($this->getReference(LocationFixtures::LOCATION_4_REFERENCE));
                    break;
                case 4:
                    $event->setLocation($this->getReference(LocationFixtures::LOCATION_5_REFERENCE));
                    break;
                case 5:
                    $event->setLocation($this->getReference(LocationFixtures::LOCATION_6_REFERENCE));
                    break;
            }

            for ($i = 0; $i < ParticipantFixtures::PARTICIPANT_NUMBER - 1; $i++) {
                $planner = $this->getReference('PARTICIPANT_' . $i . '_REFERENCE');
                $event->setPlanner($planner);
            }

            $nbPlaces = mt_rand(1, 12) * 5;
            $event->setPlaces($nbPlaces);

            if ($event->getState()->getLabel() == State::STATE_OPENED) {
                for ($i = 0; $i < rand(0, $nbPlaces); $i++) {
                    $participantId = rand(0, ParticipantFixtures::PARTICIPANT_NUMBER - 1);
                    $participant = $this->getReference('PARTICIPANT_' . $participantId . '_REFERENCE');
                    $event->addParticipant($participant);
                }
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
