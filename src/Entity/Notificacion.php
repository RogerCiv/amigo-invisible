<?php

namespace App\Entity;

use App\Repository\NotificacionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificacionRepository::class)]
class Notificacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $mensaje = null;

    #[ORM\Column]
    private ?bool $leida = null;

    #[ORM\ManyToOne(inversedBy: 'notificacions')]
    private ?User $usuarioDestino = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMensaje(): ?string
    {
        return $this->mensaje;
    }

    public function setMensaje(string $mensaje): static
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    public function isLeida(): ?bool
    {
        return $this->leida;
    }

    public function setLeida(bool $leida): static
    {
        $this->leida = $leida;

        return $this;
    }

    public function getUsuarioDestino(): ?User
    {
        return $this->usuarioDestino;
    }

    public function setUsuarioDestino(?User $usuarioDestino): static
    {
        $this->usuarioDestino = $usuarioDestino;

        return $this;
    }
}
