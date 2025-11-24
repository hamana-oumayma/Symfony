<?php
// src/Repository/VoitureRepository.php

namespace App\Repository;

use App\Entity\Voiture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Voiture>
 */
class VoitureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voiture::class);
    }

    // ATELIER 5 - Recherche par modèle avec DQL
    public function findByModele(int $modeleId): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT v, m
            FROM App\Entity\Voiture v
            JOIN v.modeleEntity m
            WHERE m.id = :modeleId
            ORDER BY v.serie ASC
        ')
            ->setParameter('modeleId', $modeleId);

        return $query->getResult();
    }

    // ATELIER 5 - Recherche des voitures par pays du modèle
    public function findByPaysModele(string $pays): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT v, m
            FROM App\Entity\Voiture v
            JOIN v.modeleEntity m
            WHERE m.pays = :pays
            ORDER BY v.prixJour DESC
        ')
            ->setParameter('pays', $pays);

        return $query->getResult();
    }

    // ATELIER 5 - Recherche avec prix minimum
    public function findByPrixMin(float $prixMin): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT v
            FROM App\Entity\Voiture v
            WHERE v.prixJour >= :prixMin
            ORDER BY v.prixJour ASC
        ')
            ->setParameter('prixMin', $prixMin);

        return $query->getResult();
    }

    // ATELIER 5 - Statistiques sur les voitures
    public function getStatistiques(): array
    {
        $em = $this->getEntityManager();

        // Prix moyen
        $queryPrixMoyen = $em->createQuery('
            SELECT AVG(v.prixJour) as prixMoyen
            FROM App\Entity\Voiture v
        ');

        // Nombre de voitures par modèle
        $queryCountByModele = $em->createQuery('
            SELECT m.libelle, COUNT(v.id) as nbVoitures
            FROM App\Entity\Voiture v
            JOIN v.modeleEntity m
            GROUP BY m.id
        ');

        return [
            'prixMoyen' => $queryPrixMoyen->getSingleScalarResult(),
            'countByModele' => $queryCountByModele->getResult(),
        ];
    }

    // ATELIER 5 - Mise à jour en masse avec DQL
    public function updatePrixPourModele(int $modeleId, float $nouveauPrix): int
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            UPDATE App\Entity\Voiture v
            SET v.prixJour = :nouveauPrix
            WHERE v.modeleEntity = :modeleId
        ')
            ->setParameter('nouveauPrix', $nouveauPrix)
            ->setParameter('modeleId', $modeleId);

        return $query->execute();
    }

    // ATELIER 5 - Suppression en masse avec DQL
    public function deleteVoituresAnciennes(\DateTime $dateLimite): int
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            DELETE FROM App\Entity\Voiture v
            WHERE v.dateMiseEnMarche < :dateLimite
        ')
            ->setParameter('dateLimite', $dateLimite);

        return $query->execute();
    }

    // Méthode de base pour sauvegarder (utile pour les tests)
    public function save(Voiture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Méthode de base pour supprimer (utile pour les tests)
    public function remove(Voiture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}