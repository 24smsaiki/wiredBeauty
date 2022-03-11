<?php

namespace App\Entity;

use App\Repository\SkinbiosenseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkinbiosenseRepository::class)]
class Skinbiosense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $productCode;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'skinbiosenses')]
    private $user;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $zoneCode;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $scoreSkinbiosense;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $sessionId;

    #[ORM\Column(type: 'float', nullable: true)]
    private $mesure;

    #[ORM\Column(type: 'string', nullable: true)]
    private $importId;

    #[ORM\Column(type: 'float', nullable: true)]
    private $kpi;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductCode(): ?string
    {
        return $this->productCode;
    }

    public function setProductCode(?string $productCode): self
    {
        $this->productCode = $productCode;

        return $this;
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

    public function getZoneCode(): ?int
    {
        return $this->zoneCode;
    }

    public function setZoneCode(?int $zoneCode): self
    {
        $this->zoneCode = $zoneCode;

        return $this;
    }

    public function getScoreSkinbiosense(): ?int
    {
        return $this->scoreSkinbiosense;
    }

    public function setScoreSkinbiosense(?int $scoreSkinbiosense): self
    {
        $this->scoreSkinbiosense = $scoreSkinbiosense;

        return $this;
    }

    public function getSessionId(): ?int
    {
        return $this->sessionId;
    }

    public function setSessionId(?int $sessionId): self
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    public function getMesure(): ?float
    {
        return $this->mesure;
    }

    public function setMesure(?float $mesure): self
    {
        $this->mesure = $mesure;

        return $this;
    }

    public function getImportId(): ?string
    {
        return $this->importId;
    }

    public function setImportId(?string $importId): self
    {
        $this->importId = $importId;

        return $this;
    }

    public function getKpi(): ?float
    {
        return $this->kpi;
    }

    public function setKpi(?float $kpi): self
    {
        $this->kpi = $kpi;

        return $this;
    }
}
