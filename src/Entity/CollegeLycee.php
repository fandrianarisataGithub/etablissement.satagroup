<?php

namespace App\Entity;

use App\Entity\Etablissement;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CollegeLyceeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=CollegeLyceeRepository::class)
 */
class CollegeLycee extends Etablissement
{

    /**
     * @ORM\OneToMany(targetEntity=Classe::class, mappedBy="collegeLycee")
     */
    private $classes;

    public function __construct()
    {
        $this->classes = new ArrayCollection();
    }

    /**
     * @return Collection|Classe[]
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClass(Classe $class): self
    {
        if (!$this->classes->contains($class)) {
            $this->classes[] = $class;
            $class->setCollegeLycee($this);
        }

        return $this;
    }

    public function removeClass(Classe $class): self
    {
        if ($this->classes->removeElement($class)) {
            // set the owning side to null (unless already changed)
            if ($class->getCollegeLycee() === $this) {
                $class->setCollegeLycee(null);
            }
        }

        return $this;
    }
}
