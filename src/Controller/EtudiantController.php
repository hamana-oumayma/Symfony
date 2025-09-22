<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EtudiantController extends AbstractController
{
    #[Route('/etudiant', name: 'etudiant')]
    public function index(): Response
    {
        return new Response("bonjour mes etudiants");
    }

    #[Route('/etudiant/{id}', name: 'afficher_etudiant')]
    public function afficherEtudiant(int $id): Response
    {
        return new Response("bonjour l'etudiant numéro " . $id);
    }

    #[Route('/etudiant/nom/{name}', name: 'etudiant_name')]
    public function voirNom($name): Response
    {
        return $this->render('etudiant/etudiant.html.twig',['name' => $name,
        ]);
    }
}
