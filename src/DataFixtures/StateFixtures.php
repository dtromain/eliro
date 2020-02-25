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
    public const STATE_CANCELLED_REFERENCE = 'state-cancelled';

    public function load(ObjectManager $manager)
    {

        $creating = new State();
        $creating->setLabel(State::STATE_CREATING);
        $this->addReference(self::STATE_CREATING_REFERENCE, $creating);
        $manager->persist($creating);

        $open = new State();
        $open->setLabel(State::STATE_OPEN);
        $this->addReference(self::STATE_OPEN_REFERENCE, $open);
        $manager->persist($open);

        $close = new State();
        $close->setLabel(State::STATE_CLOSE);
        $this->addReference(self::STATE_CLOSE_REFERENCE, $close);
        $manager->persist($close);

        $ongoing = new State();
        $ongoing->setLabel(State::STATE_PENDING);
        $this->addReference(self::STATE_ONGOING_REFERENCE, $ongoing);
        $manager->persist($ongoing);

        $cancelled = new State();
        $cancelled->setLabel(State::STATE_CANCELLED);
        $this->addReference(self::STATE_CANCELLED_REFERENCE, $cancelled);
        $manager->persist($cancelled);

        $manager->flush();
    }
}