<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsuarioRepository")
 */
class Usuario implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank
     */
    private $mail;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Persona")
     * @ORM\JoinColumn(name="numeroEmpleado",referencedColumnName="numero_empleado",nullable=false, onDelete="CASCADE")
     */
    private $numeroEmpleado;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $Password;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserRoles")
     * @ORM\JoinColumn(name="UserRole",referencedColumnName="role",nullable=false) 
     */
    private $UserRole = [];

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Type("string")
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Type("string")
     */
    private $apellidos;

   
    /**
     * @ORM\Column(type="string", length=250)
     *
     */
    private $imagen;

    public function getnumeroEmpleado(): ?Persona
    {
        return $this->numeroEmpleado;
    }

    public function setnumeroEmpleado(Persona $numeroEmpleado): self
    {
        $this->numeroEmpleado = $numeroEmpleado;

        return $this;
    }


    public function getUserRole(): ?UserRoles
    {
        return NULL;
    }


    public function setUserRole(UserRoles $UserRole): self
    {
        $this->UserRole = $UserRole;

        return $this;
    }


    public function setPassword(string $Password): self
    {
        $this->Password = $Password;

        return $this;
    }


    public function getRoles(): array
    {
        
        $UserRole[] = $this->UserRole;

        return array_unique($UserRole);
    }

    public function eraseCredentials()
    {
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->Password;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;
        return $this;
    }
    
    public function getMail()
    {
        return $this->mail;
    }

    public function getUsername()
    {
        return $this->mail;
    }

    public function __toString()
    {
        return $this->mail;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(?string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    public function setImagen($imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }
}
