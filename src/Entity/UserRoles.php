<?php

namespace App\Entity;

use Symfony\Component\Security\Core\Role\Role;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRolesRepository")
 */
class UserRoles extends Role
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Type("string")
     */
     
    private $Role;

    public function getRole(): ?string
    {
        return $this->Role;
    }

    public function setRole(?string $Role): self
    {
        $this->Role = $Role;

        return $this;
    }

    /**
    * @codeCoverageIgnore
     */
    public function __toString(): string
    {
        return $this->Role;
    }

}
