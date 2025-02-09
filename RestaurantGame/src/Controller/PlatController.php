<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/plat')]
class PlatController extends AbstractController
{
    private $platRepository;
    private $entityManager;

    public function __construct(PlatRepository $platRepository, EntityManagerInterface $entityManager)
    {
        $this->platRepository = $platRepository;
        $this->entityManager = $entityManager;
    }

    // Créer un nouveau plat
    #[Route('/create', name: 'plat_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        // Vérification des données requises
        if (!isset($data['nom'], $data['temps_cuisson'], $data['prix'])) {
            return $this->json(['error' => 'Nom, temps de cuisson, et prix requis'], Response::HTTP_BAD_REQUEST);
        }

        // Création du plat
        $plat = new Plat();
        $plat->setNom($data['nom']);
        $plat->setDescription($data['description'] ?? ''); // Description optionnelle
        $plat->setTempsCuisson($data['temps_cuisson']);
        $plat->setPrix($data['prix']);
        $plat->setDateCreation(new \DateTime());
        $plat->setDateModification(new \DateTime());

        // Sauvegarde dans la base de données
        $this->entityManager->persist($plat);
        $this->entityManager->flush();

        return $this->json(['message' => 'Plat créé avec succès'], Response::HTTP_CREATED);
    }

    // Modifier un plat existant
    #[Route('/edit/{id}', name: 'plat_edit', methods: ['PUT'])]
    public function edit(int $id, Request $request): Response
    {
        // Trouver le plat
        $plat = $this->platRepository->find($id);

        if (!$plat) {
            return $this->json(['error' => 'Plat introuvable'], Response::HTTP_NOT_FOUND);
        }

        // Modifier les données du plat
        $data = json_decode($request->getContent(), true);
        $plat->setNom($data['nom'] ?? $plat->getNom());
        $plat->setTempsCuisson($data['temps_cuisson'] ?? $plat->getTempsCuisson());
        $plat->setPrix($data['prix'] ?? $plat->getPrix());
        $plat->setDateModification(new \DateTime());

        // Sauvegarde des modifications
        $this->entityManager->flush();

        return $this->json(['message' => 'Plat modifié avec succès']);
    }

    // Supprimer un plat
    #[Route('/delete/{id}', name: 'plat_delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        // Trouver le plat
        $plat = $this->platRepository->find($id);

        if (!$plat) {
            return $this->json(['error' => 'Plat introuvable'], Response::HTTP_NOT_FOUND);
        }

        // Supprimer le plat de la base de données
        $this->entityManager->remove($plat);
        $this->entityManager->flush();

        return $this->json(['message' => 'Plat supprimé avec succès']);
    }

    // Lister tous les plats
    #[Route('/list', name: 'plat_list', methods: ['GET'])]
    public function list(): Response
    {
        // Récupérer tous les plats
        $plats = $this->platRepository->findAll();
        return $this->json($plats);
    }
}
