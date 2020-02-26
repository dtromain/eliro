<?php

namespace App\DataFixtures;

use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class StateFixtures extends Fixture
{
    public const STATE_CREATING_REFERENCE = 'state-creating';
    public const STATE_OPENED_REFERENCE = 'state-opened';
    public const STATE_CLOSED_REFERENCE = 'state-closed';
    public const STATE_PENDING_REFERENCE = 'state-pending';
    public const STATE_FINISHED_REFERENCE = 'state-finished';
    public const STATE_CANCELLED_REFERENCE = 'state-cancelled';
    public const STATE_HISTORISED_REFERENCE = 'state-historised';

    public function load(ObjectManager $manager)
    {

        $creating = new State();
        $creating->setLabel(State::STATE_CREATING);
        $this->addReference(self::STATE_CREATING_REFERENCE, $creating);
        $manager->persist($creating);

        $opened = new State();
        $opened->setLabel(State::STATE_OPENED);
        $this->addReference(self::STATE_OPENED_REFERENCE, $opened);
        $manager->persist($opened);

        $closed = new State();
        $closed->setLabel(State::STATE_CLOSED);
        $this->addReference(self::STATE_CLOSED_REFERENCE, $closed);
        $manager->persist($closed);

        $pending = new State();
        $pending->setLabel(State::STATE_PENDING);
        $this->addReference(self::STATE_PENDING_REFERENCE, $pending);
        $manager->persist($pending);

        $finished = new State();
        $finished->setLabel(State::STATE_FINISHED);
        $this->addReference(self::STATE_FINISHED_REFERENCE, $finished);
        $manager->persist($finished);

        $cancelled = new State();
        $cancelled->setLabel(State::STATE_CANCELLED);
        $this->addReference(self::STATE_CANCELLED_REFERENCE, $cancelled);
        $manager->persist($cancelled);

        $historised = new State();
        $historised->setLabel(State::STATE_HISTORISED);
        $this->addReference(self::STATE_HISTORISED_REFERENCE, $historised);
        $manager->persist($historised);

        $manager->flush();
    }
}