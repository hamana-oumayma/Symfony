<?php
// src/Repository/ModeleRepository.php

namespace App\Repository;

use App\Entity\Modele;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ModeleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Modele::class);
    }

    public function addModele(string $libelle, string $pays): Modele
    {
        $em = $this->getEntityManager();
        $modele = new Modele();
        $modele->setLibelle($libelle);
        $modele->setPays($pays);

        $em->persist($modele);
        $em->flush();

        return $modele;
    }

    public function findAllModeles(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT m
            FROM App\Entity\Modele m
            ORDER BY m.libelle ASC
        ');
        return $query->getResult();
    }

    public function updateModele(int $id, string $libelle, string $pays): int
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            UPDATE App\Entity\Modele m
            SET m.libelle = :libelle,
                m.pays = :pays
            WHERE m.id = :id
        ')
            ->setParameter('libelle', $libelle)
            ->setParameter('pays', $pays)
            ->setParameter('id', $id);

        return $query->execute();
    }

    public function deleteModele(int $id): int
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            DELETE FROM App\Entity\Modele m
            WHERE m.id = :id
        ')
            ->setParameter('id', $id);

        return $query->execute();
    }
}