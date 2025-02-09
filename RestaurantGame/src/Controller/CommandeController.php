<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    // Liste des clients avec commandes en cours
    #[Route('/clients', name: 'commande_clients')]
    public function clients(CommandeRepository $commandeRepository): Response
    {
        // Exemple : récupérer les clients ayant une commande en cours
        $clients = $commandeRepository->findBy(['statut' => 'en_attente']);
        
        return $this->render('commande/clients.html.twig', [
            'clients' => $clients,
        ]);
    }

    // Modifier le statut d’une commande
    #[Route('/status/{id}', name: 'commande_status')]
    public function status(int $id, CommandeRepository $commandeRepository, EntityManagerInterface $em): Response
    {
        $commande = $commandeRepository->find($id);

        if (!$commande) {
            return $this->json(['error' => 'Commande non trouvée'], Response::HTTP_NOT_FOUND);
        }

        // Logique pour changer le statut de la commande
        $statuts = ['en_attente', 'en_preparation', 'terminee', 'annulee'];
        $currentStatusIndex = array_search($commande->getStatut(), $statuts);
        $newStatusIndex = ($currentStatusIndex + 1) % count($statuts); // Passe au statut suivant

        $commande->setStatut($statuts[$newStatusIndex]);

        $em->flush();

        return $this->json(['message' => 'Statut de la commande mis à jour avec succès', 'nouveau_statut' => $commande->getStatut()]);
    }

    // Lister toutes les commandes
    #[Route('/list', name: 'commande_list')]
    public function list(CommandeRepository $commandeRepository): Response
    {
        // Exemple : récupérer toutes les commandes
        $commandes = $commandeRepository->findAll();
        
        return $this->render('commande/list.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    // Détail d’une commande
    #[Route('/detail/{id}', name: 'commande_detail')]
    public function detail(int $id, CommandeRepository $commandeRepository): Response
    {
        $commande = $commandeRepository->find($id);

        if (!$commande) {
            return $this->json(['error' => 'Commande non trouvée'], Response::HTTP_NOT_FOUND);
        }

        return $this->render('commande/detail.html.twig', [
            'commande' => $commande,
        ]);
    }

    // Ajouter une commande
    #[Route('/ajouter', name: 'commande_ajouter', methods: ['POST'])]
    public function ajouter(Request $request, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['utilisateur_id'], $data['plats'])) {
            return $this->json(['error' => 'Données manquantes'], Response::HTTP_BAD_REQUEST);
        }

        $commande = new Commande();
        $commande->setUtilisateurId($data['utilisateur_id']);
        $commande->setMontantTotal($data['montant_total']);
        $commande->setStatut('en_attente');
        $commande->setDateCreation(new \DateTime());
        $commande->setDateModification(new \DateTime());

        foreach ($data['plats'] as $platData) {
            // Logique pour ajouter des détails de plat à la commande
            // Ajouter les plats commandés dans la commande (par exemple, avec une table de détail)
        }

        $em->persist($commande);
        $em->flush();

        return $this->json(['message' => 'Commande ajoutée avec succès', 'commande_id' => $commande->getId()]);
    }

    // Supprimer une commande
    #[Route('/supprimer/{id}', name: 'commande_supprimer', methods: ['DELETE'])]
    public function supprimer(int $id, CommandeRepository $commandeRepository, EntityManagerInterface $em): Response
    {
        $commande = $commandeRepository->find($id);

        if (!$commande) {
            return $this->json(['error' => 'Commande non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($commande);
        $em->flush();

        return $this->json(['message' => 'Commande supprimée avec succès']);
    }

    // Ajouter un plat à une commande
    #[Route('/ajouter-plat/{commandeId}/{platId}', name: 'commande_ajouter_plat', methods: ['POST'])]
    public function ajouterPlat(int $commandeId, int $platId, CommandeRepository $commandeRepository, EntityManagerInterface $em): Response
    {
        $commande = $commandeRepository->find($commandeId);

        if (!$commande) {
            return $this->json(['error' => 'Commande non trouvée'], Response::HTTP_NOT_FOUND);
        }

        // Logique pour ajouter le plat à la commande
        // Par exemple, récupérer le plat par son ID et l'ajouter aux détails de la commande

        return $this->json(['message' => 'Plat ajouté à la commande avec succès']);
    }

    // Retirer un plat d’une commande
    #[Route('/retirer-plat/{commandeId}/{platId}', name: 'commande_retirer_plat', methods: ['DELETE'])]
    public function retirerPlat(int $commandeId, int $platId, CommandeRepository $commandeRepository, EntityManagerInterface $em): Response
    {
        $commande = $commandeRepository->find($commandeId);

        if (!$commande) {
            return $this->json(['error' => 'Commande non trouvée'], Response::HTTP_NOT_FOUND);
        }

        // Logique pour retirer un plat de la commande

        return $this->json(['message' => 'Plat retiré de la commande avec succès']);
    }
}
