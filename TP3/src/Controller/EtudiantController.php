<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class EtudiantController extends AbstractController
{
    #[Route('/list', name: 'Liste')]
    public function listEtudiant(): Response
    {
        // Liste étudiants
        $etudiants = array('Ali', 'Med', 'Jasser');

        // Liste des modules
        $modules = array(
            array(
                "nom" => "Symfony",
                "id" => 1,
                "enseignant" => "Ali",
                "nbHeures" => 42,
                "date" => "12-12-2025"
            ),
            array(
                "nom" => "JEE",
                "id" => 2,
                "enseignant" => "Med",
                "nbHeures" => 31,
                "date" => "12-10-2025"
            ),
            array(
                "nom" => "BD",
                "id" => 3,
                "enseignant" => "Islam",
                "nbHeures" => 21,
                "date" => "12-09-2025"
            )
        );

        return $this->render('etudiant/list.html.twig', [
            'etudiants' => $etudiants,
            'ListeModules' => $modules
        ]);


    }
    #[Route('/affectation', name: 'Affectation')]
    public function affecter(): Response
    {
        return $this->render('etudiant/affecter.html.twig');
    }

    #[Route('/indexFils', name: 'index_fils')]
    public function indexFils(): Response
    {
        return $this->render('etudiant/index.html.twig');
    }
}