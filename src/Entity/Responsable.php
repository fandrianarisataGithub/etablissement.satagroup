<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ResponsableRepository;

/**
 * @ORM\Entity(repositoryClass=ResponsableRepository::class)
 */
class Responsable extends Membre
{
    
}
