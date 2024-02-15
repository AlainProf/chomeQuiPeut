<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntrepriseRepository::class)]
class Entreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $contact = null;

    #[ORM\OneToMany(targetEntity: OffreEmploi::class, mappedBy: 'entreprise')]
    private Collection $offresEmplois;

    public function __construct()
    {
        $this->offresEmplois = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return Collection<int, OffreEmploi>
     */
    public function getOffresEmplois(): Collection
    {
        return $this->offresEmplois;
    }

    public function addOffresEmploi(OffreEmploi $offresEmploi): static
    {
        if (!$this->offresEmplois->contains($offresEmploi)) {
            $this->offresEmplois->add($offresEmploi);
            $offresEmploi->setEntreprise($this);
        }

        return $this;
    }

    public function removeOffresEmploi(OffreEmploi $offresEmploi): static
    {
        if ($this->offresEmplois->removeElement($offresEmploi)) {
            // set the owning side to null (unless already changed)
            if ($offresEmploi->getEntreprise() === $this) {
                $offresEmploi->setEntreprise(null);
            }
        }

        return $this;
    }
}
