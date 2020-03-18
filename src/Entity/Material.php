<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MaterialRepository")
 */
class Material
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $Nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Descripcion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Local")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Oficina;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Precio;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario", cascade={"persist"})
     * @ORM\JoinColumn(name="Usuario",referencedColumnName="mail", nullable=true)
     */
    private $Usuario;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Tipo;

    /**
     * @ORM\Column(type="string", length=200,  options={"default":"yes"})
     */
    private $disponible = "yes";

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

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

    public function getDescripcion(): ?string
    {
        return $this->Descripcion;
    }

    public function setDescripcion(?string $Descripcion): self
    {
        $this->Descripcion = $Descripcion;

        return $this;
    }

    public function getOficina(): ?Local
    {
        return $this->Oficina;
    }

    public function setOficina(?Local $Oficina): self
    {
        $this->Oficina = $Oficina;

        return $this;
    }

    public function getPrecio(): ?int
    {
        return $this->Precio;
    }

    public function setPrecio(?int $Precio): self
    {
        $this->Precio = $Precio;

        return $this;
    }

    public function getUsuario(): ?string
    {
        return $this->Usuario;
    }

    public function setUsuario(?Usuario $Usuario): self
    {
        $this->Usuario = $Usuario;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->Tipo;
    }

    public function setTipo(?string $Tipo): self
    {
        $this->Tipo = $Tipo;

        return $this;
    }

    public function getDisponible(): ?string
    {
        return $this->disponible;
    }

    public function setDisponible(string $disponible): self
    {
        $this->disponible = $disponible;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
