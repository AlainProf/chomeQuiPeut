<?php

namespace App\Entity;

use App\Repository\PostulationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostulationRepository::class)]
class Postulation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datePostulee = null;

    #[ORM\Column(length: 50)]
    private ?string $statut = null;

    #[ORM\ManyToOne(inversedBy: 'postulations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Chomeur $chomeurPostulant = null;

    #[ORM\ManyToOne(inversedBy: 'postulations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OffreEmploi $offreEmploiPostulee = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePostulee(): ?\DateTimeInterface
    {
        return $this->datePostulee;
    }

    public function setDatePostulee(\DateTimeInterface $datePostulee): static
    {
        $this->datePostulee = $datePostulee;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getChomeurPostulant(): ?Chomeur
    {
        return $this->chomeurPostulant;
    }

    public function setChomeurPostulant(?Chomeur $chomeurPostulant): static
    {
        $this->chomeurPostulant = $chomeurPostulant;

        return $this;
    }

    public function getOffreEmploiPostulee(): ?OffreEmploi
    {
        return $this->offreEmploiPostulee;
    }

    public function setOffreEmploiPostulee(?OffreEmploi $offreEmploiPostulee): static
    {
        $this->offreEmploiPostulee = $offreEmploiPostulee;

        return $this;
    }
}
