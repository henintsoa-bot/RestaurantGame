<?php

namespace App\Entity;

use App\Repository\EtatJeuRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtatJeuRepository::class)]
class EtatJeu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $positionPersonnage = null;

    #[ORM\Column(type: 'integer')]
    private ?int $platsPrepares = null;

    #[ORM\Column(type: 'integer')]
    private ?int $platsLivres = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $ingredientActuel = null;

    public function __construct()
    {
        $this->platsPrepares = 0;
        $this->platsLivres = 0;
        $this->ingredientActuel = '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function getPositionPersonnage(): ?string
    {
        return $this->positionPersonnage;
    }

    public function setPositionPersonnage(string $positionPersonnage): self
    {
        $this->positionPersonnage = $positionPersonnage;
        return $this;
    }

    public function getPlatsPrepares(): ?int
    {
        return $this->platsPrepares;
    }

    public function setPlatsPrepares(int $platsPrepares): self
    {
        $this->platsPrepares = $platsPrepares;
        return $this;
    }

    public function getPlatsLivres(): ?int
    {
        return $this->platsLivres;
    }

    public function setPlatsLivres(int $platsLivres): self
    {
        $this->platsLivres = $platsLivres;
        return $this;
    }

    public function getIngredientActuel(): ?string
    {
        return $this->ingredientActuel;
    }

    public function setIngredientActuel(string $ingredientActuel): self
    {
        $this->ingredientActuel = $ingredientActuel;
        return $this;
    }
}
