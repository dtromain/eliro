<?php

namespace App\DataFixtures;


use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ParticipantFixtures extends Fixture
{
    private $encoder;

    public const PARTICIPANT_NUMBER = 50;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $root = new Participant();
        $root->setFirstname("root");
        $root->setLastname("root");
        $root->setPhone("0639458712");
        $root->setMail("root@eliro.com");
        $root->setUsername("root");
        $root->setIsAdmin(true);
        $root->setPassword(
            $this->encoder->encodePassword($root, 'password')
        );
        $root->setCampus($this->getReference(CampusFixtures::CAMPUS_NANTES_REFERENCE));
        $this->setReference('PARTICIPANT_0_REFERENCE', $root);
        $manager->persist($root);

        $martin = new Participant();
        $martin->setFirstname("Martin");
        $martin->setLastname("DUPONT");
        $martin->setPhone("0759179237");
        $martin->setMail("dupont@eliro.com");
        $martin->setUsername("mdupont");
        $martin->setPassword(
            $this->encoder->encodePassword($martin, 'password')
        );
        $martin->setCampus($this->getReference(CampusFixtures::CAMPUS_RENNES_REFERENCE));
        $this->setReference('PARTICIPANT_1_REFERENCE', $martin);
        $manager->persist($martin);

        $julie = new Participant();
        $julie->setFirstname("Julie");
        $julie->setLastname("DESCHAMPS");
        $julie->setPhone("0649287436");
        $julie->setMail("deschamps@eliro.com");
        $julie->setUsername("jdeschamps");
        $julie->setPassword(
            $this->encoder->encodePassword($julie, 'password')
        );
        $julie->setCampus($this->getReference(CampusFixtures::CAMPUS_NANTES_REFERENCE));
        $this->setReference('PARTICIPANT_2_REFERENCE', $julie);
        $manager->persist($julie);

        for ($i = 3; $i < self::PARTICIPANT_NUMBER; $i++) {
            $participant = new Participant();
            $participant->setFirstname($faker->firstName);
            $participant->setLastname($faker->lastName);
            $participant->setPhone($faker->phoneNumber);
            $mail = '' .  $participant->getFirstname() . '.' . strtolower ($participant->getLastname()) . '@' . $faker->domainName;
            $participant->setMail($mail);
            $username = '' . $participant->getFirstname() . '.' . $participant->getLastname();
            $participant->setUsername($username);
            $participant->setPassword(
                $this->encoder->encodePassword($participant, 'password')
            );
            $rand = rand(1, self::PARTICIPANT_NUMBER);
            switch ($rand) {
                case ($rand <= 40):
                    $participant->setCampus($this->getReference(CampusFixtures::CAMPUS_NANTES_REFERENCE));
                    break;
                case ($rand <=50):
                    $participant->setCampus($this->getReference(CampusFixtures::CAMPUS_QUIMPER_REFERENCE));
                    break;
                case ($rand <= 80):
                    $participant->setCampus($this->getReference(CampusFixtures::CAMPUS_RENNES_REFERENCE));
                    break;
                case ($rand <= 100):
                    $participant->setCampus($this->getReference(CampusFixtures::CAMPUS_NIORT_REFERENCE));
                    break;

            }
            $this->setReference('PARTICIPANT_'.$i.'_REFERENCE', $participant);
            $manager->persist($participant);
        }
        $manager->flush();
    }
}
