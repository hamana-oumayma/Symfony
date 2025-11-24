<?php
// src/Entity/Voiture.php

namespace App\Entity;

use App\Repository\VoitureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoitureRepository::class)]
#[ORM\Table(name: 'voiture')]
class Voiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $serie = null;

    #[ORM\Column(name: 'date_mise_en_marche', type: 'date')]
    private ?\DateTimeInterface $dateMiseEnMarche = null;

    #[ORM\Column(nullable: true)]
    private ?float $prixJour = null;

    // RELATION AVEC MODELE
    #[ORM\ManyToOne(targetEntity: Modele::class, inversedBy: 'voitures')]
    #[ORM\JoinColumn(name: 'modele_id', referencedColumnName: 'id', nullable: true)]
    private ?Modele $modeleEntity = null;

    #[ORM\OneToMany(mappedBy: 'voiture', targetEntity: Location::class)]
    private Collection $locations;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
    }

    // Getters et setters pour modeleEntity
    public function getModeleEntity(): ?Modele
    {
        return $this->modeleEntity;
    }

    public function setModeleEntity(?Modele $modeleEntity): static
    {
        $this->modeleEntity = $modeleEntity;
        return $this;
    }

    // ... autres getters et setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSerie(): ?string
    {
        return $this->serie;
    }

    public function setSerie(string $serie): static
    {
        $this->serie = $serie;
        return $this;
    }

    public function getDateMiseEnMarche(): ?\DateTimeInterface
    {
        return $this->dateMiseEnMarche;
    }

    public function setDateMiseEnMarche(\DateTimeInterface $dateMiseEnMarche): static
    {
        $this->dateMiseEnMarche = $dateMiseEnMarche;
        return $this;
    }

    public function getPrixJour(): ?float
    {
        return $this->prixJour;
    }

    public function setPrixJour(float $prixJour): static
    {
        $this->prixJour = $prixJour;
        return $this;
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): static
    {
        if (!$this->locations->contains($location)) {
            $this->locations->add($location);
            $location->setVoiture($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): static
    {
        if ($this->locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getVoiture() === $this) {
                $location->setVoiture(null);
            }
        }

        return $this;
    }
}