<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureFormType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VoitureController extends AbstractController
{
    #[Route('/voitures', name: 'app_voiture')]
    public function listeVoiture(VoitureRepository $vr): Response
    {
        $voitures = $vr->findAll();

        return $this->render('voiture/listeVoiture.html.twig', [
            'listeVoiture' => $voitures,
        ]);
    }

    #[Route('/addVoiture', name: 'add_voiture')]
    public function addVoiture(Request $request, EntityManagerInterface $em): Response
    {
        $voiture = new Voiture();
        $form = $this->createForm(VoitureFormType::class, $voiture);
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

    #[Route('/voiture/delete/{id}', name: 'voiture_delete')]
    public function delete(EntityManagerInterface $em, VoitureRepository $vr, $id): Response
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

    #[Route('/voiture/update/{id}', name: 'voiture_update')]
    public function updateVoiture(Request $request, EntityManagerInterface $em, VoitureRepository $vr, $id): Response
    {
        $voiture = $vr->find($id);

        if (!$voiture) {
            $this->addFlash('error', 'Voiture non trouvée!');
            return $this->redirectToRoute('app_voiture');
        }

        $form = $this->createForm(VoitureFormType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($voiture);
            $em->flush();

            $this->addFlash('success', 'Voiture mise à jour avec succès!');
            return $this->redirectToRoute('app_voiture');
        }

        return $this->render('voiture/updateVoiture.html.twig', [
            'editFormVoiture' => $form->createView(),
        ]);
    }
}