<?php

namespace App\Entity;

use App\Entity\Etablissement;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UniversiteRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=UniversiteRepository::class)
 */
class Universite extends Etablissement
{
    

    /**
     * @ORM\OneToMany(targetEntity=Filiere::class, mappedBy="universite")
     */
    private $filieres;

    public function __construct()
    {
        $this->filieres = new ArrayCollection();
    }

    /**
     * @return Collection|Filiere[]
     */
    public function getFilieres(): Collection
    {
        return $this->filieres;
    }

    public function addFiliere(Filiere $filiere): self
    {
        if (!$this->filieres->contains($filiere)) {
            $this->filieres[] = $filiere;
            $filiere->setUniversite($this);
        }

        return $this;
    }

    public function removeFiliere(Filiere $filiere): self
    {
        if ($this->filieres->removeElement($filiere)) {
            // set the owning side to null (unless already changed)
            if ($filiere->getUniversite() === $this) {
                $filiere->setUniversite(null);
            }
        }

        return $this;
    }
}
