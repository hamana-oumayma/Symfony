<?php
// src/Controller/ModeleController.php

namespace App\Controller;

use App\Repository\ModeleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModeleController extends AbstractController
{
    #[Route('/modele/add', name: 'modele_add')]
    public function add(ModeleRepository $rep): Response
    {
        $modele = $rep->addModele('Clio', 'France');
        return new Response('Modele ajouté avec ID: ' . $modele->getId());
    }

    #[Route('/modele/list', name: 'modele_list')]
    public function list(ModeleRepository $repo): Response
    {
        $modeles = $repo->findAllModeles();

        $output = '<h2>Liste des modèles</h2><ul>';
        foreach ($modeles as $m) {
            $output .= '<li>ID: ' . $m->getId() . ' | Libelle: ' . $m->getLibelle() . ' | Pays: ' . $m->getPays() . '</li>';
        }
        $output .= '</ul>';

        return new Response($output);
    }
}