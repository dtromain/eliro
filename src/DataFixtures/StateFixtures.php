<?php

namespace App\DataFixtures;

use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class StateFixtures extends Fixture
{
    public const STATE_CREATING_REFERENCE = 'state-creating';
    public const STATE_OPEN_REFERENCE = 'state-open';
    public const STATE_CLOSE_REFERENCE = 'state-close';
    public const STATE_ONGOING_REFERENCE = 'state-ongoing';

    public function load(ObjectManager $manager)
    {
        $creating = new State();
        $creating->setLabel("En création");
        $this->addReference(self::STATE_CREATING_REFERENCE, $creating);
        $manager->persist($creating);

        $open = new State();
        $open->setLabel("Ouvert");
        $this->addReference(self::STATE_OPEN_REFERENCE, $open);
        $manager->persist($open);

        $close = new State();
        $close->setLabel("Fermé");
        $this->addReference(self::STATE_CLOSE_REFERENCE, $close);
        $manager->persist($close);

        $ongoing = new State();
        $ongoing->setLabel("En cours");
        $this->addReference(self::STATE_ONGOING_REFERENCE, $ongoing);
        $manager->persist($ongoing);

        $manager->flush();
    }
}
