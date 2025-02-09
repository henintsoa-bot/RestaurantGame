<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/utilisateur')]
class UtilisateurController extends AbstractController
{

    
    #[Route('/inscription', name: 'utilisateur_inscription', methods: ['POST'])]
    public function inscription(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, UtilisateurRepository $utilisateurRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email'], $data['mot_de_passe'])) {
            return $this->json(['error' => 'Email et mot de passe requis'], Response::HTTP_BAD_REQUEST);
        }

        // Vérification de l'unicité de l'email
        $existingUser = $utilisateurRepository->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return $this->json(['error' => 'Cet email est déjà utilisé.'], Response::HTTP_BAD_REQUEST);
        }

        $utilisateur = new Utilisateur();
        $utilisateur->setEmail($data['email']);
        $utilisateur->setNom($data['nom'] ?? '');
        $utilisateur->setRoles(['ROLE_USER']);
        $utilisateur->setPassword($passwordHasher->hashPassword($utilisateur, $data['mot_de_passe']));
        $utilisateur->setDateCreation(new \DateTime());
        $utilisateur->setDateModification(new \DateTime());

        $em->persist($utilisateur);
        $em->flush();

        return $this->json(['message' => 'Utilisateur créé avec succès'], Response::HTTP_CREATED);
    }

    #[Route('/connexion', name: 'utilisateur_connexion', methods: ['POST'])]
    public function connexion(Request $request, UtilisateurRepository $utilisateurRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email'], $data['mot_de_passe'])) {
            return $this->json(['error' => 'Email et mot de passe requis'], Response::HTTP_BAD_REQUEST);
        }

        $utilisateur = $utilisateurRepository->findOneBy(['email' => $data['email']]);

        if (!$utilisateur || !$passwordHasher->isPasswordValid($utilisateur, $data['mot_de_passe'])) {
            return $this->json(['error' => 'Identifiants invalides'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json(['message' => 'Connexion réussie']);
    }

    #[Route('/me', name: 'utilisateur_me', methods: ['GET'])]
    public function getMe(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'Utilisateur non authentifié'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'nom' => $user->getNom(),
            'roles' => $user->getRoles(),
            'dateCreation' => $user->getDateCreation()->format('Y-m-d H:i:s'),
            'dateModification' => $user->getDateModification()->format('Y-m-d H:i:s'),
        ]);
    }

    #[Route('/update', name: 'utilisateur_update', methods: ['PUT'])]
    public function update(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $data = json_decode($request->getContent(), true);

        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'Utilisateur non authentifié'], Response::HTTP_UNAUTHORIZED);
        }

        if (isset($data['nom'])) {
            $user->setNom($data['nom']);
        }

        if (isset($data['mot_de_passe'])) {
            $user->setPassword($passwordHasher->hashPassword($user, $data['mot_de_passe']));
        }

        $user->setDateModification(new \DateTime());

        $em->persist($user);
        $em->flush();

        return $this->json(['message' => 'Informations mises à jour avec succès']);
    }

    #[Route('/delete', name: 'utilisateur_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'Utilisateur non authentifié'], Response::HTTP_UNAUTHORIZED);
        }

        $em->remove($user);
        $em->flush();

        return $this->json(['message' => 'Utilisateur supprimé avec succès']);
    }
}
