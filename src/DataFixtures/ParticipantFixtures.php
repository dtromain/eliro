<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ParticipantFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $root = new Participant();
        $root->setFirstname("root");
        $root->setLastname("root");
        $root->setPhone("0606060606");
        $root->setMail("root@eliro.com");
        $root->setUsername("root");

        $root->setPassword(
            $this->encoder->encodePassword($root, 'password')
        );

        $manager->persist($root);

        for ($i = 0; $i < 10; $i++) {
            $participant = new Participant();
            $participant->setFirstname($faker->firstName);
            $participant->setLastname($faker->lastName);
            $participant->setPhone($faker->phoneNumber);
            $participant->setMail($faker->email);
            $participant->setUsername($faker->userName);
            $participant->setPassword(
                $this->encoder->encodePassword($participant, $faker->password)
            );
            switch (rand(0,3)) {
                case 0:
                    $participant->setCampus($this->getReference(CityFixtures::CAMPUS_NANTES_REFERENCE));
                    break;
                case 1:
                    $participant->setCampus($this->getReference(CityFixtures::CITY_QUIMPER_REFERENCE));
                    break;
                case 2:
                    $participant->setCampus($this->getReference(CityFixtures::CAMPUS_RENNES_REFERENCE));
                    break;
                case 3:
                    $participant->setCampus($this->getReference(CityFixtures::CAMPUS_NIORT_REFERENCE));
                    break;

            }
            $manager->persist($participant);
        }
        $manager->flush();
    }
}
