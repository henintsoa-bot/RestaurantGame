<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Repository\RecetteRepository;
use App\Entity\Plat;
use App\Entity\Ingredient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recette')]
class RecetteController extends AbstractController
{
    private $recetteRepository;
    private $entityManager;

    public function __construct(RecetteRepository $recetteRepository, EntityManagerInterface $entityManager)
    {
        $this->recetteRepository = $recetteRepository;
        $this->entityManager = $entityManager;
    }

    // Créer une nouvelle recette
    #[Route('/create', name: 'recette_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        // Vérification des données requises
        if (!isset($data['plat_id'], $data['ingredient_id'], $data['quantite'])) {
            return $this->json(['error' => 'Plat, ingrédient et quantité requis'], Response::HTTP_BAD_REQUEST);
        }

        // Récupération des entités Plat et Ingrédient
        $plat = $this->getDoctrine()->getRepository(Plat::class)->find($data['plat_id']);
        $ingredient = $this->getDoctrine()->getRepository(Ingredient::class)->find($data['ingredient_id']);

        if (!$plat || !$ingredient) {
            return $this->json(['error' => 'Plat ou ingrédient introuvable'], Response::HTTP_NOT_FOUND);
        }

        // Création de la recette
        $recette = new Recette();
        $recette->setPlat($plat);
        $recette->setIngredient($ingredient);
        $recette->setQuantite($data['quantite']);

        // Sauvegarde dans la base de données
        $this->entityManager->persist($recette);
        $this->entityManager->flush();

        return $this->json(['message' => 'Recette créée avec succès'], Response::HTTP_CREATED);
    }

    // Modifier une recette existante
    #[Route('/edit/{id}', name: 'recette_edit', methods: ['PUT'])]
    public function edit(int $id, Request $request): Response
    {
        // Trouver la recette
        $recette = $this->recetteRepository->find($id);

        if (!$recette) {
            return $this->json(['error' => 'Recette introuvable'], Response::HTTP_NOT_FOUND);
        }

        // Modifier la quantité de l'ingrédient
        $data = json_decode($request->getContent(), true);
        $recette->setQuantite($data['quantite'] ?? $recette->getQuantite());

        // Sauvegarde des modifications
        $this->entityManager->flush();

        return $this->json(['message' => 'Recette modifiée avec succès']);
    }

    // Supprimer une recette
    #[Route('/delete/{id}', name: 'recette_delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        // Trouver la recette
        $recette = $this->recetteRepository->find($id);

        if (!$recette) {
            return $this->json(['error' => 'Recette introuvable'], Response::HTTP_NOT_FOUND);
        }

        // Supprimer la recette
        $this->entityManager->remove($recette);
        $this->entityManager->flush();

        return $this->json(['message' => 'Recette supprimée avec succès']);
    }

    // Lister toutes les recettes
    #[Route('/list', name: 'recette_list', methods: ['GET'])]
    public function list(): Response
    {
        // Récupérer toutes les recettes
        $recettes = $this->recetteRepository->findAll();
        return $this->json($recettes);
    }
}
