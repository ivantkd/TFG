<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketsRepository")
 */
class Tickets
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario")
     * @ORM\JoinColumn(name="usermail",referencedColumnName="mail",nullable=false, onDelete="CASCADE")
     */
    private $usermail;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Departamento")
     * @ORM\JoinColumn(name="departamento_id",referencedColumnName="id",nullable=false)
     */
    private $Departamento;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $TipoIncidente;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $Dispositivo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Explicacion;

    /**
     *
     * @ORM\Column(name="solved", type="string", options={"default":"no"}, nullable=true)
     */
    private $Solved = "no";

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Solucion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsermail(): ?Usuario
    {
       return $this->usermail;
    }

    public function setUsermail(Usuario $usermail): self
    {
        $this->usermail = $usermail;

        return $this;
    }

    public function getDepartamento(): ?Departamento
    {
        return $this->Departamento;
    }

    public function setDepartamento(Departamento $Departamento): self
    {
        $this->Departamento = $Departamento;

        return $this;
    }

    public function getTipoIncidente(): ?string
    {
        return $this->TipoIncidente;
    }

    public function setTipoIncidente(string $TipoIncidente): self
    {
        $this->TipoIncidente = $TipoIncidente;

        return $this;
    }

    public function getDispositivo(): ?string
    {
        return $this->Dispositivo;
    }

    public function setDispositivo(?string $Dispositivo): self
    {
        $this->Dispositivo = $Dispositivo;

        return $this;
    }

    public function getExplicacion(): ?string
    {
        return $this->Explicacion;
    }

    public function setExplicacion(?string $Explicacion): self
    {
        $this->Explicacion = $Explicacion;

        return $this;
    }

    public function getSolved(): ?string
    {
        return $this->Solved;
    }

    public function setSolved(?string $Solved): self
    {
        $this->Solved = $Solved;

        return $this;
    }

    public function getSolucion(): ?string
    {
        return $this->Solucion;
    }

    public function setSolucion(?string $Solucion): self
    {
        $this->Solucion = $Solucion;

        return $this;
    }
}
