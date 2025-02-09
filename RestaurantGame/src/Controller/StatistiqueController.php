<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('/statistique')]
class StatistiqueController extends AbstractController
{
    // Route pour afficher les statistiques de ventes
    #[Route('/ventes', name: 'statistique_ventes')]
    public function ventes(): Response
    {
        // Calcul du montant total des ventes (exemple : récupérer la somme des ventes depuis la base de données)
        $totalVentes = 0; // À remplacer par la logique réelle de calcul

        return $this->render('statistique/ventes.html.twig', [
            'totalVentes' => $totalVentes,
        ]);
    }

    // Route pour afficher les statistiques sur les plats
    #[Route('/plats', name: 'statistique_plats')]
    public function plats(): Response
    {
        // Calcul du nombre total de plats servis (exemple : récupérer le nombre total de plats depuis la base de données)
        $totalPlats = 0; // À remplacer par la logique réelle de calcul

        return $this->render('statistique/plats.html.twig', [
            'totalPlats' => $totalPlats,
        ]);
    }
}
