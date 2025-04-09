<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtatRepository::class)]
class Etat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, Sortie>
     */
    #[ORM\OneToMany(targetEntity: Sortie::class, mappedBy: 'Etat')]
    private Collection $organisateur;

    public function __construct()
    {
        $this->organisateur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getOrganisateur(): Collection
    {
        return $this->organisateur;
    }

    public function addOrganisateur(Sortie $organisateur): static
    {
        if (!$this->organisateur->contains($organisateur)) {
            $this->organisateur->add($organisateur);
            $organisateur->setEtat($this);
        }

        return $this;
    }

    public function removeOrganisateur(Sortie $organisateur): static
    {
        if ($this->organisateur->removeElement($organisateur)) {
            // set the owning side to null (unless already changed)
            if ($organisateur->getEtat() === $this) {
                $organisateur->setEtat(null);
            }
        }

        return $this;
    }
}
