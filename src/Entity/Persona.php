<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonaRepository")
 */
class Persona
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $Numero_Empleado;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Local")
     * @ORM\JoinColumn(name="Local_id",referencedColumnName="id", nullable=false)
     */
    private $Local_id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank
     */
    private $Correo;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Departamento")
     * @ORM\JoinColumn(name="departamento_id",referencedColumnName="id",nullable=false)
     */
    private $Departamento;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Type("string")
     * @Assert\NotBlank
     */
    private $Nombre;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Type("string")
     * @Assert\NotBlank
     */
    private $Apellidos;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Type("string")
     * @Assert\NotBlank
     */
    private $Cargo;
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\NotBlank
     */
    private $Telefono;

    public function getNumero_Empleado(): ?int
    {
        return $this->Numero_Empleado;
    }

    public function getLocalId(): ?Local
    {
        return $this->Local_id;
    }

    public function setLocalId(?Local $Local_id): self
    {
        $this->Local_id = $Local_id;

        return $this;
    }

    public function getDepartamento(): ?Departamento
    {
        return $this->Departamento;
    }

    public function setDepartamento(?Departamento $Departamento): self
    {
        $this->Departamento = $Departamento;

        return $this;
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

    public function getApellidos(): ?string
    {
        return $this->Apellidos;
    }

    public function setApellidos(string $Apellidos): self
    {
        $this->Apellidos = $Apellidos;

        return $this;
    }

    public function getCargo(): ?string
    {
        return $this->Cargo;
    }

    public function setCargo(string $Cargo): self
    {
        $this->Cargo = $Cargo;

        return $this;
    }

    public function getCorreo(): ?string
    {
        return $this->Correo;
    }

    public function setCorreo(?string $Correo): self
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

    public function __toString()
    {
        return $this->Nombre;
    }
}
