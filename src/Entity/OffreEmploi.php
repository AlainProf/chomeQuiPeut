<?php

namespace App\Entity;

use App\Repository\OffreEmploiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffreEmploiRepository::class)]
class OffreEmploi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(name:'salaireAnnuel')]
    private ?int $salaireAnnuel = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datePublication = null;

    #[ORM\ManyToOne(inversedBy: 'offresEmplois', cascade:['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Entreprise $entreprise = null;

    #[ORM\ManyToMany(targetEntity: Chomeur::class, mappedBy: 'offresEmplois')]
    private Collection $chomeurs;

    #[ORM\OneToMany(targetEntity: Postulation::class, mappedBy: 'offreEmploiPostulee')]
    private Collection $postulations;

    public function __construct()
    {
        $this->chomeurs = new ArrayCollection();
        $this->postulations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSalaireAnnuel(): ?int
    {
        return $this->salaireAnnuel;
    }

    public function setSalaireAnnuel(int $salaireAnnuel): static
    {
        $this->salaireAnnuel = $salaireAnnuel;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTimeInterface $datePublication): static
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): static
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    /**
     * @return Collection<int, Chomeur>
     */
    public function getChomeurs(): Collection
    {
        return $this->chomeurs;
    }

    public function addChomeur(Chomeur $chomeur): static
    {
        if (!$this->chomeurs->contains($chomeur)) {
            $this->chomeurs->add($chomeur);
            $chomeur->addOffresEmploi($this);
        }

        return $this;
    }

    public function removeChomeur(Chomeur $chomeur): static
    {
        if ($this->chomeurs->removeElement($chomeur)) {
            $chomeur->removeOffresEmploi($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Postulation>
     */
    public function getPostulations(): Collection
    {
        return $this->postulations;
    }

    public function addPostulation(Postulation $postulation): static
    {
        if (!$this->postulations->contains($postulation)) {
            $this->postulations->add($postulation);
            $postulation->setOffreEmploiPostulee($this);
        }

        return $this;
    }

    public function removePostulation(Postulation $postulation): static
    {
        if ($this->postulations->removeElement($postulation)) {
            // set the owning side to null (unless already changed)
            if ($postulation->getOffreEmploiPostulee() === $this) {
                $postulation->setOffreEmploiPostulee(null);
            }
        }

        return $this;
    }
}
