<?php

namespace App\Entity;

use App\Repository\StatistiqueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatistiqueRepository::class)]
class Statistique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?float $ventesTotales = null;

    #[ORM\Column(type: 'integer')]
    private ?int $platsServis = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateCreation = null;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVentesTotales(): ?float
    {
        return $this->ventesTotales;
    }

    public function setVentesTotales(float $ventesTotales): self
    {
        $this->ventesTotales = $ventesTotales;
        return $this;
    }

    public function getPlatsServis(): ?int
    {
        return $this->platsServis;
    }

    public function setPlatsServis(int $platsServis): self
    {
        $this->platsServis = $platsServis;
        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }
}
