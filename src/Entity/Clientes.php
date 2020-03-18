<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientesRepository")
 */
class Clientes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     * @Assert\Type("string")
     */
    private $Nombre;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank
     * @Assert\Type("string")
     * 
     */
    private $Correo;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Assert\NotBlank
     */
    private $Telefono;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\NotBlank
     * 
     */
    private $Direccion;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Type("string")
     */
    private $Responsable;

    /**
     * @ORM\Column(type="string", length=254, nullable=true)
     * @Assert\Type("string")
     */
    private $Servicio;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->Nombre;
    }

    public function setNombre(string $Nombre): self
    {
        $this->Nombre = $Nombre;

        return $this;
    }

    public function getCorreo(): ?string
    {
        return $this->Correo;
    }

    public function setCorreo(string $Correo): self
    {
        $this->Correo = $Correo;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->Telefono;
    }

    public function setTelefono(?string $Telefono): self
    {
        $this->Telefono = $Telefono;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->Direccion;
    }

    public function setDireccion(?string $Direccion): self
    {
        $this->Direccion = $Direccion;

        return $this;
    }

    public function getResponsable(): ?string
    {
        return $this->Responsable;
    }

    public function setResponsable(?string $Responsable): self
    {
        $this->Responsable = $Responsable;

        return $this;
    }

    public function getServicio(): ?string
    {
        return $this->Servicio;
    }

    public function setServicio(?string $Servicio): self
    {
        $this->Servicio = $Servicio;

        return $this;
    }
}
