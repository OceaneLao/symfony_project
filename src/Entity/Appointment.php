<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    private ?User $id_user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $creation_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $start_datetime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $end_datetime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(?\DateTimeInterface $creation_date): static
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getStartDatetime(): ?\DateTimeInterface
    {
        return $this->start_datetime;
    }

    public function setStartDatetime(?\DateTimeInterface $start_datetime): static
    {
        $this->start_datetime = $start_datetime;

        return $this;
    }

    public function getEndDatetime(): ?\DateTimeInterface
    {
        return $this->end_datetime;
    }

    public function setEndDatetime(?\DateTimeInterface $end_datetime): static
    {
        $this->end_datetime = $end_datetime;

        return $this;
    }
}
