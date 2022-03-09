<?php

namespace App\Entity;

use App\Repository\PerceptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PerceptionRepository::class)]
class Perception
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'perceptions')]
    private $user;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $scorePerception;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getScorePerception(): ?int
    {
        return $this->scorePerception;
    }

    public function setScorePerception(?int $scorePerception): self
    {
        $this->scorePerception = $scorePerception;

        return $this;
    }
}
