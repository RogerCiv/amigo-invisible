<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(targetEntity: Emparejamiento::class, mappedBy: 'usuarioRegala')]
    private Collection $participaciones;

    #[ORM\OneToMany(targetEntity: Emparejamiento::class, mappedBy: 'usuarioRecibe')]
    private Collection $regalos;

    #[ORM\ManyToMany(targetEntity: GrupoAmigos::class, mappedBy: 'usuarios')]
    private Collection $grupoAmigos;

    #[ORM\OneToMany(targetEntity: Invitacion::class, mappedBy: 'usuarioInvitado')]
    private Collection $invitacionesRecibidas;

    #[ORM\OneToMany(targetEntity: Invitacion::class, mappedBy: 'usuarioCreador')]
    private Collection $invitacions;

    #[ORM\OneToMany(targetEntity: Notificacion::class, mappedBy: 'usuarioDestino')]
    private Collection $notificacions;

    #[ORM\ManyToMany(targetEntity: Sorteo::class, mappedBy: 'usuarios')]
    private Collection $sorteos;

    public function __construct()
    {
        $this->participaciones = new ArrayCollection();
        $this->regalos = new ArrayCollection();
        $this->grupoAmigos = new ArrayCollection();
        $this->invitacionesRecibidas = new ArrayCollection();
        $this->invitacions = new ArrayCollection();
        $this->notificacions = new ArrayCollection();
        $this->sorteos = new ArrayCollection();
    
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Emparejamiento>
     */
    public function getParticipaciones(): Collection
    {
        return $this->participaciones;
    }

    public function addParticipacione(Emparejamiento $participacione): static
    {
        if (!$this->participaciones->contains($participacione)) {
            $this->participaciones->add($participacione);
            $participacione->setUsuarioRegala($this);
        }

        return $this;
    }

    public function removeParticipacione(Emparejamiento $participacione): static
    {
        if ($this->participaciones->removeElement($participacione)) {
            // set the owning side to null (unless already changed)
            if ($participacione->getUsuarioRegala() === $this) {
                $participacione->setUsuarioRegala(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Emparejamiento>
     */
    public function getRegalos(): Collection
    {
        return $this->regalos;
    }

    public function addRegalo(Emparejamiento $regalo): static
    {
        if (!$this->regalos->contains($regalo)) {
            $this->regalos->add($regalo);
            $regalo->setUsuarioRecibe($this);
        }

        return $this;
    }

    public function removeRegalo(Emparejamiento $regalo): static
    {
        if ($this->regalos->removeElement($regalo)) {
            // set the owning side to null (unless already changed)
            if ($regalo->getUsuarioRecibe() === $this) {
                $regalo->setUsuarioRecibe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GrupoAmigos>
     */
    public function getGrupoAmigos(): Collection
    {
        return $this->grupoAmigos;
    }

    public function addGrupoAmigo(GrupoAmigos $grupoAmigo): static
    {
        if (!$this->grupoAmigos->contains($grupoAmigo)) {
            $this->grupoAmigos->add($grupoAmigo);
            $grupoAmigo->addUsuario($this);
        }

        return $this;
    }

    public function removeGrupoAmigo(GrupoAmigos $grupoAmigo): static
    {
        if ($this->grupoAmigos->removeElement($grupoAmigo)) {
            $grupoAmigo->removeUsuario($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Invitacion>
     */
    public function getInvitacionesRecibidas(): Collection
    {
        return $this->invitacionesRecibidas;
    }

    public function addInvitacionesRecibida(Invitacion $invitacionesRecibida): static
    {
        if (!$this->invitacionesRecibidas->contains($invitacionesRecibida)) {
            $this->invitacionesRecibidas->add($invitacionesRecibida);
            $invitacionesRecibida->setUsuarioInvitado($this);
        }

        return $this;
    }

    public function removeInvitacionesRecibida(Invitacion $invitacionesRecibida): static
    {
        if ($this->invitacionesRecibidas->removeElement($invitacionesRecibida)) {
            // set the owning side to null (unless already changed)
            if ($invitacionesRecibida->getUsuarioInvitado() === $this) {
                $invitacionesRecibida->setUsuarioInvitado(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invitacion>
     */
    public function getInvitacions(): Collection
    {
        return $this->invitacions;
    }

    public function addInvitacion(Invitacion $invitacion): static
    {
        if (!$this->invitacions->contains($invitacion)) {
            $this->invitacions->add($invitacion);
            $invitacion->setUsuarioCreador($this);
        }

        return $this;
    }

    public function removeInvitacion(Invitacion $invitacion): static
    {
        if ($this->invitacions->removeElement($invitacion)) {
            // set the owning side to null (unless already changed)
            if ($invitacion->getUsuarioCreador() === $this) {
                $invitacion->setUsuarioCreador(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notificacion>
     */
    public function getNotificacions(): Collection
    {
        return $this->notificacions;
    }

    public function addNotificacion(Notificacion $notificacion): static
    {
        if (!$this->notificacions->contains($notificacion)) {
            $this->notificacions->add($notificacion);
            $notificacion->setUsuarioDestino($this);
        }

        return $this;
    }

    public function removeNotificacion(Notificacion $notificacion): static
    {
        if ($this->notificacions->removeElement($notificacion)) {
            // set the owning side to null (unless already changed)
            if ($notificacion->getUsuarioDestino() === $this) {
                $notificacion->setUsuarioDestino(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sorteo>
     */
    public function getSorteos(): Collection
    {
        return $this->sorteos;
    }

    public function addSorteo(Sorteo $sorteo): static
    {
        if (!$this->sorteos->contains($sorteo)) {
            $this->sorteos->add($sorteo);
            $sorteo->addUsuario($this);
        }

        return $this;
    }

    public function removeSorteo(Sorteo $sorteo): static
    {
        if ($this->sorteos->removeElement($sorteo)) {
            $sorteo->removeUsuario($this);
        }

        return $this;
    }

}
