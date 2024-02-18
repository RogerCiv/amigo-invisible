<?php

namespace App\Entity;

use App\Repository\SorteoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SorteoRepository::class)]
class Sorteo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $presupuesto = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\OneToMany(targetEntity: Emparejamiento::class, mappedBy: 'sorteo')]
    private Collection $emparejamientos;

    public function __construct()
    {
        $this->emparejamientos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPresupuesto(): ?string
    {
        return $this->presupuesto;
    }

    public function setPresupuesto(string $presupuesto): static
    {
        $this->presupuesto = $presupuesto;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * @return Collection<int, Emparejamiento>
     */
    public function getEmparejamientos(): Collection
    {
        return $this->emparejamientos;
    }

    public function addEmparejamiento(Emparejamiento $emparejamiento): static
    {
        if (!$this->emparejamientos->contains($emparejamiento)) {
            $this->emparejamientos->add($emparejamiento);
            $emparejamiento->setSorteo($this);
        }

        return $this;
    }

    public function removeEmparejamiento(Emparejamiento $emparejamiento): static
    {
        if ($this->emparejamientos->removeElement($emparejamiento)) {
            // set the owning side to null (unless already changed)
            if ($emparejamiento->getSorteo() === $this) {
                $emparejamiento->setSorteo(null);
            }
        }

        return $this;
    }
}