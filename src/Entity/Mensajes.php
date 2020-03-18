<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use \DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MensajesRepository")
 */
class Mensajes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario")
     * @ORM\JoinColumn(name="mailUsuario",referencedColumnName="mail",nullable=false)
     */
    private $mailUsuario;

    /**
     * @ORM\Column(type="text", length=254)
     * @Assert\NotBlank
     */
    
    private $cuerpo;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Type("string")
     * @Assert\NotBlank
     */
    private $asunto;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    public function __construct(){
        $this->fecha = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getmailUsuario(): ?Usuario
    {
        return $this->mailUsuario;
    }

    public function setmailusuario(Usuario $mail_usuario): self
    {
        $this->mailUsuario = $mail_usuario;

        return $this;
    }

    public function getCuerpo(): ?string
    {
        return $this->cuerpo;
    }

    public function setCuerpo(string $cuerpo): self
    {
        $this->cuerpo = $cuerpo;

        return $this;
    }

    public function getAsunto(): ?string
    {
        return $this->asunto;
    }

    public function setAsunto(string $asunto): self
    {
        $this->asunto = $asunto;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }
}
