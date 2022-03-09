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
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $resetToken;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ResetPassword::class)]
    private $resetPasswords;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Skinbiosense::class)]
    private $skinbiosenses;

    #[ORM\Column(type: 'string', nullable: true)]
    private $user;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Perception::class)]
    private $perceptions;

    public function __construct()
    {
        $this->resetPasswords = new ArrayCollection();
        $this->skinbiosenses = new ArrayCollection();
        $this->perceptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string)$this->email;
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

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    /**
     * <<<<<<< HEAD
     * @return Collection<int, ResetPassword>
     */
    public function getResetPasswords(): Collection
    {
        return $this->resetPasswords;
    }

    public function addResetPassword(ResetPassword $resetPassword): self
    {
        if (!$this->resetPasswords->contains($resetPassword)) {
            $this->resetPasswords[] = $resetPassword;
            $resetPassword->setUser($this);
        }
        return $this;
    }

    public function getSkinbiosenses(): Collection
    {
        return $this->skinbiosenses;
    }

    public function addSkinbiosense(Skinbiosense $skinbiosense): self
    {
        if (!$this->skinbiosenses->contains($skinbiosense)) {
            $this->skinbiosenses[] = $skinbiosense;
            $skinbiosense->setUser($this);
        }

        return $this;
    }

    public function removeResetPassword(ResetPassword $resetPassword): self
    {
        if ($this->resetPasswords->removeElement($resetPassword)) {
            // set the owning side to null (unless already changed)
            if ($resetPassword->getUser() === $this) {
                $resetPassword->setUser(null);
            }
        }
    }

    public function removeSkinbiosense(Skinbiosense $skinbiosense): self
    {
        if ($this->skinbiosenses->removeElement($skinbiosense)) {
            // set the owning side to null (unless already changed)
            if ($skinbiosense->getUser() === $this) {
                $skinbiosense->setUser(null);
            }
        }

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(?string $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Perception>
     */
    public function getPerceptions(): Collection
    {
        return $this->perceptions;
    }

    public function addPerception(Perception $perception): self
    {
        if (!$this->perceptions->contains($perception)) {
            $this->perceptions[] = $perception;
            $perception->setUser($this);
        }

        return $this;
    }

    public function removePerception(Perception $perception): self
    {
        if ($this->perceptions->removeElement($perception)) {
            // set the owning side to null (unless already changed)
            if ($perception->getUser() === $this) {
                $perception->setUser(null);
            }
        }

        return $this;
    }
}
