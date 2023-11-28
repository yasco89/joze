<?php

namespace App\Entity;

use App\Repository\DisponibiliteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DisponibiliteRepository::class)]
#[ORM\Table(name: "disponibilite")]
#[ORM\Index(name: "IDX_2CBACE2F20AE7A39", columns: ["habitat_id_id"])]

class Disponibilite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $dateDebut;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $dateFin;

    #[ORM\Column(type: 'integer')]
    private int $chambreReserves;


    #[ORM\ManyToOne(targetEntity: "Habitat")]
    #[ORM\JoinColumn(name: "habitat_id_id", referencedColumnName: "id")]
    private ?Habitat $habitatId;

    // getters and setters
    public function getId(): int
    {
        return $this->id;
    }

    public function getDateDebut(): \DateTime
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTime $dateDebut): self
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function getDateFin(): \DateTime
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTime $dateFin): self
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    public function getChambreReserves(): int
    {
        return $this->chambreReserves;
    }

    public function setChambreReserves(int $chambreReserves): self
    {
        $this->chambreReserves = $chambreReserves;
        return $this;
    }



    public function getHabitatId(): ?Habitat
    {
        return $this->habitatId;
    }

    public function setHabitatId(?Habitat $habitatId): self
    {
        $this->habitatId = $habitatId;
        return $this;
    }
}
