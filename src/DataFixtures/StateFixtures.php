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
        $creating->setLabel("creating");
        $this->addReference(self::STATE_CREATING_REFERENCE, $creating);

        $open = new State();
        $open->setLabel("open");
        $this->addReference(self::STATE_OPEN_REFERENCE, $open);

        $close = new State();
        $close->setLabel("close");
        $this->addReference(self::STATE_CLOSE_REFERENCE, $close);

        $ongoing = new State();
        $ongoing->setLabel("ongoing");
        $this->addReference(self::STATE_ONGOING_REFERENCE, $ongoing);

        $manager->persist($creating);
        $manager->persist($open);
        $manager->persist($close);
        $manager->persist($ongoing);
        $manager->flush();

        $manager->flush();
    }
}
