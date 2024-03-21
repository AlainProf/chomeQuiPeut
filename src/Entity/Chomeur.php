<?php

namespace App\Entity;

use App\Repository\ChomeurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ChomeurRepository::class)]
class Chomeur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\Length(min:2, minMessage:'deux caractères minimum')]
    #[Assert\Length(max:100, maxMessage:'cent caractères maximum')]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $courriel = null;

    #[ORM\Column(length: 10)]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateInscription = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Adresse $adresse = null;

    #[ORM\ManyToMany(targetEntity: OffreEmploi::class, inversedBy: 'chomeurs')]
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

    public function getCourriel(): ?string
    {
        return $this->courriel;
    }

    public function setCourriel(string $courriel): static
    {
        $this->courriel = $courriel;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): static
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    public function setAdresse(?Adresse $adresse): static
    {
        $this->adresse = $adresse;

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
            $offresEmploi->addChomeur($this);
        }

        return $this;
    }

    public function removeOffresEmploi(OffreEmploi $offresEmploi): static
    {
        $this->offresEmplois->removeElement($offresEmploi);

        return $this;
    }
}
