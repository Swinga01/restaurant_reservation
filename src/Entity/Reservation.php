<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "La date de r?servation est requise")]
    #[Assert\GreaterThanOrEqual("today", message: "La date doit ?tre aujourd'hui ou dans le futur")]
    private ?\DateTimeInterface $dateReservation = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\NotBlank(message: "L'heure est requise")]
    private ?\DateTimeInterface $heure = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le nombre de personnes est requis")]
    #[Assert\Positive(message: "Le nombre de personnes doit ?tre positif")]
    #[Assert\Range(
        min: 1,
        max: 20,
        notInRangeMessage: "Le nombre de personnes doit ?tre entre {{ min }} et {{ max }}"
    )]
    private ?int $nombrePersonnes = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le commentaire ne peut pas dépasser 255 caractères"
    )]
    private ?string $commentaire = null;

    #[ORM\ManyToOne]
    #[Assert\NotNull(message: "Veuillez sélectionner une table")]
    private ?\App\Entity\TableResto $tableResto = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->dateReservation;
    }

    public function setDateReservation(\DateTimeInterface $dateReservation): static
    {
        $this->dateReservation = $dateReservation;
        return $this;
    }

    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(\DateTimeInterface $heure): static
    {
        $this->heure = $heure;
        return $this;
    }

    public function getNombrePersonnes(): ?int
    {
        return $this->nombrePersonnes;
    }

    public function setNombrePersonnes(int $nombrePersonnes): static
    {
        $this->nombrePersonnes = $nombrePersonnes;
        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    public function getTableResto(): ?\App\Entity\TableResto
    {
        return $this->tableResto;
    }

    public function setTableResto(?\App\Entity\TableResto $tableResto): static
    {
        $this->tableResto = $tableResto;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTime();
    }
}
