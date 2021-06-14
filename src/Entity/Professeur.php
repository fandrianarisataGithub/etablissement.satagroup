<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfesseurRepository;

/**
 * @ORM\Entity(repositoryClass=ProfesseurRepository::class)
 */
class Professeur extends Membre
{
    
}
