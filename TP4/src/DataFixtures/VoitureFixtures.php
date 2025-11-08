<?php

namespace App\DataFixtures;

use App\Entity\Voiture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VoitureFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $voiture1 = new Voiture();
        $voiture1->setSerie('1234');
        $voiture1->setDateMiseEnMarche(new \DateTime('2025-09-01'));
        $voiture1->setModele('BMW');
        $voiture1->setPrixJour(100);
        $manager->persist($voiture1);

        $voiture2 = new Voiture();
        $voiture2->setSerie('5678');
        $voiture2->setDateMiseEnMarche(new \DateTime('2025-08-31'));
        $voiture2->setModele('FORD');
        $voiture2->setPrixJour(50);
        $manager->persist($voiture2);

        $voiture3 = new Voiture();
        $voiture3->setSerie('9123');
        $voiture3->setDateMiseEnMarche(new \DateTime('2025-10-01'));
        $voiture3->setModele('AUDI');
        $voiture3->setPrixJour(150);
        $manager->persist($voiture3);

        $manager->flush();
    }
}