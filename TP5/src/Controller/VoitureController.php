<?php
// src/Controller/VoitureController.php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureForm;
use App\Repository\VoitureRepository;
use App\Repository\ModeleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VoitureController extends AbstractController
{
    // ATELIER 4 - Affichage de la liste des voitures
    #[Route('/voitures', name: 'app_voiture')]
    public function listeVoiture(VoitureRepository $vr): Response
    {
        $voitures = $vr->findAll();
        return $this->render('voiture/listeVoiture.html.twig', [
            'listeVoiture' => $voitures,
        ]);
    }

    // ATELIER 4 - Ajout d'une voiture
    #[Route('/addVoiture', name: 'add_voiture')]
    public function addVoiture(Request $request, EntityManagerInterface $em): Response
    {
        $voiture = new Voiture();
        $form = $this->createForm(VoitureForm::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($voiture);
            $em->flush();

            $this->addFlash('success', 'Voiture ajoutée avec succès!');
            return $this->redirectToRoute('app_voiture');
        }

        return $this->render('voiture/addVoiture.html.twig', [
            'formV' => $form->createView(),
        ]);
    }

    // ATELIER 4 - Suppression d'une voiture
    #[Route('/voiture/delete/{id}', name: 'voitureDelete')]
    public function delete(EntityManagerInterface $em, VoitureRepository $vr, int $id): Response
    {
        $voiture = $vr->find($id);

        if (!$voiture) {
            $this->addFlash('error', 'Voiture non trouvée!');
            return $this->redirectToRoute('app_voiture');
        }

        $em->remove($voiture);
        $em->flush();

        $this->addFlash('success', 'Voiture supprimée avec succès!');
        return $this->redirectToRoute('app_voiture');
    }

    // ATELIER 4 - Modification d'une voiture
    #[Route('/voiture/update/{id}', name: 'voitureUpdate')]
    public function updateVoiture(Request $request, EntityManagerInterface $em, VoitureRepository $vr, int $id): Response
    {
        $voiture = $vr->find($id);

        if (!$voiture) {
            $this->addFlash('error', 'Voiture non trouvée!');
            return $this->redirectToRoute('app_voiture');
        }

        $editForm = $this->createForm(VoitureForm::class, $voiture);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->persist($voiture);
            $em->flush();

            $this->addFlash('success', 'Voiture modifiée avec succès!');
            return $this->redirectToRoute('app_voiture');
        }

        return $this->render('voiture/updateVoiture.html.twig', [
            'editFormVoiture' => $editForm->createView(),
            'voiture' => $voiture,
        ]);
    }

    // ATELIER 5 - Ajout de voitures avec des modèles (méthode alternative)
    #[Route('/addVoitures', name: 'add_voitures')]
    public function addVoitures(EntityManagerInterface $em, ModeleRepository $modeleRepo): Response
    {
        // Vérifier s'il existe des modèles, sinon en créer
        $modeles = $modeleRepo->findAll();

        if (empty($modeles)) {
            // Créer quelques modèles de base
            $modele1 = $modeleRepo->addModele('Clio', 'France');
            $modele2 = $modeleRepo->addModele('Megane', 'France');
            $modele3 = $modeleRepo->addModele('Golf', 'Allemagne');
            $modele4 = $modeleRepo->addModele('A3', 'Allemagne');

            $modeles = [$modele1, $modele2, $modele3, $modele4];
        }

        // Ajouter quelques voitures avec des modèles
        $voituresData = [
            ['1234', new \DateTime('2025-01-15'), $modeles[0], 80.0],
            ['5678', new \DateTime('2025-02-20'), $modeles[1], 120.0],
            ['9012', new \DateTime('2025-03-10'), $modeles[2], 100.0],
            ['3456', new \DateTime('2025-04-05'), $modeles[3], 150.0],
        ];

        $voituresAdded = 0;
        foreach ($voituresData as $data) {
            // Vérifier si la voiture existe déjà
            $existingVoiture = $em->getRepository(Voiture::class)->findOneBy(['serie' => $data[0]]);

            if (!$existingVoiture) {
                $voiture = new Voiture();
                $voiture->setSerie($data[0]);
                $voiture->setDateMiseEnMarche($data[1]);
                $voiture->setModeleEntity($data[2]);
                $voiture->setPrixJour($data[3]);

                $em->persist($voiture);
                $voituresAdded++;
            }
        }

        $em->flush();

        return new Response(sprintf('%d voitures ajoutées avec succès!', $voituresAdded));
    }

    // ATELIER 5 - Filtrer les voitures par modèle
    #[Route("/voitures-par-modele", name: 'voitures_par_modele')]
    public function voituresParModele(Request $request, VoitureRepository $vr, EntityManagerInterface $em): Response
    {
        $modeleId = $request->query->get('modele');
        $voitures = [];

        if ($modeleId) {
            $voitures = $vr->findByModele((int)$modeleId);
        }

        // Récupérer tous les modèles pour le select
        $modeles = $em->getRepository(\App\Entity\Modele::class)->findAll();

        return $this->render('voiture/voituresParModele.html.twig', [
            'voitures' => $voitures,
            'modeles' => $modeles,
            'selectedModele' => $modeleId
        ]);
    }

    // ATELIER 5 - Recherche avancée avec DQL (exemple supplémentaire)
    #[Route("/voitures-cher", name: 'voitures_cher')]
    public function voituresCher(VoitureRepository $vr): Response
    {
        // Exemple: trouver les voitures avec prix journalier > 100
        $voituresCher = $vr->createQueryBuilder('v')
            ->where('v.prixJour > :prix')
            ->setParameter('prix', 100)
            ->orderBy('v.prixJour', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('voiture/voituresCher.html.twig', [
            'voitures' => $voituresCher,
        ]);
    }

    // ATELIER 5 - Détails d'une voiture avec son modèle
    #[Route("/voiture/{id}", name: 'voiture_show')]
    public function show(VoitureRepository $vr, int $id): Response
    {
        $voiture = $vr->find($id);

        if (!$voiture) {
            throw $this->createNotFoundException('Voiture non trouvée');
        }

        return $this->render('voiture/show.html.twig', [
            'voiture' => $voiture,
        ]);
    }
}