<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]




class RendezVous
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $famille = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $type = null;


    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heure_debut = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heure_fin = null;

    #[ORM\OneToMany(mappedBy: 'rdv', targetEntity: ReservRDV::class)]
    private Collection $newrdvs;

    #[ORM\Column]
    private ?int $quantite = null;

    public function __construct()
    {
        $this->newrdvs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getFamille(): ?string
    {
        return $this->famille;
    }

    public function setFamille(string $famille): static
    {
        $this->famille = $famille;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }


    public function getHeureDebut(): ?\DateTimeInterface
    {
        return $this->heure_debut;
    }

    public function setHeureDebut(\DateTimeInterface $heure_debut): static
    {
        $this->heure_debut = $heure_debut;

        return $this;
    }

    public function getHeureFin(): ?\DateTimeInterface
    {
        return $this->heure_fin;
    }

    public function setHeureFin(\DateTimeInterface $heure_fin): static
    {
        $this->heure_fin = $heure_fin;

        return $this;
    }
        /**
     * @return Collection<int, ReservRDV>
     */
    public function getNewrdvs(): Collection
    {
        return $this->newrdvs;
    }

    public function addNewrdv(ReservRDV $newrdv): static
    {
        if (!$this->newrdvs->contains($newrdv)) {
            $this->newrdvs->add($newrdv);
            $newrdv->setRdv($this);
        }

        return $this;
    }

    public function removeNewrdv(ReservRDV $newrdv): static
    {
        if ($this->newrdvs->removeElement($newrdv)) {
            // set the owning side to null (unless already changed)
            if ($newrdv->getRdv() === $this) {
                $newrdv->setRdv(null);
            }
        }

        return $this;
    }



    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }
}
