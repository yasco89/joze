<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\User;

#[ORM\Entity]
#[ORM\Table(name: "habitat")]
#[ORM\Index(name: "IDX_3B37B2E81FB8D185", columns: ["host_id"])]

class Habitat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $name;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $images = null;


    #[ORM\Column(type: "integer")]
    private int $quantite;


    #[ORM\Column(type: "integer")]
    private int $prix;

    #[ORM\Column(type: "text")]
    private string $type;

    #[ORM\Column(type: "text")]
    private string $famille;



    #[ORM\ManyToOne(targetEntity: "User")]
    #[ORM\JoinColumn(name: "host_id", referencedColumnName: "id")]
    private $host;



    #[ORM\OneToMany(mappedBy: 'habitat', targetEntity: Reservation::class)]
    private Collection $reservations;



    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }


        public function getId(): int
        {
            return $this->id;
        }

        public function getName(): string
        {
            return $this->name;
        }

        public function setName(string $name): self
        {
            $this->name = $name;
            return $this;
        }

        public function getDescription(): ?string
        {
            return $this->description;
        }

        public function setDescription(?string $description): self
        {
            $this->description = $description;
            return $this;
        }

        public function getImages(): ?string
        {
            return $this->images;
        }

        public function setImages(?string $images): self
        {
            $this->images = $images;
            return $this;
        }

        public function getQuantite(): int
        {
            return $this->quantite;
        }

        public function setQuantite(int $quantite): self
        {
            $this->quantite = $quantite;
            return $this;
        }


        public function getPrix(): int
        {
            return $this->prix;
        }

        public function setPrix(int $prix): self
        {
            $this->prix = $prix;
            return $this;
        }

        public function getType(): string
        {
            return $this->type;
        }

        public function setType(string $type): self
        {
            $this->type = $type;
            return $this;
        }



        public function getFamille(): string
        {
            return $this->famille;
        }

        public function setFamille(string $famille): self
        {
            $this->famille = $famille;
            return $this;
        }


        public function getHost(): ?\App\Entity\User

        {
            return $this->host;
        }

        // public function setHost(\User $host): self
        // {
        //     $this->host = $host;
        //     return $this;
        // }

        public function setHost(\App\Entity\User $host): self
        {
            $this->host = $host;
            return $this;
        }




        /**
         * @return Collection<int, Reservation>
         */
        public function getReservations(): Collection
        {
            return $this->reservations;
        }

        public function addReservation(Reservation $reservation): static
        {
            if (!$this->reservations->contains($reservation)) {
                $this->reservations->add($reservation);
                $reservation->setHabitat($this);
            }

            return $this;
        }

        public function removeReservation(Reservation $reservation): static
        {
            if ($this->reservations->removeElement($reservation)) {
                // set the owning side to null (unless already changed)
                if ($reservation->getHabitat() === $this) {
                    $reservation->setHabitat(null);
                }
            }

            return $this;
        }





}
