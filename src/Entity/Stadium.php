<?php

namespace App\Entity;

use App\Entity\Club;
use App\Entity\Tournament;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\AddressTrait;
use App\Repository\StadiumRepository;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=StadiumRepository::class)
 */
class Stadium
{
    use AddressTrait;
    use TimestampableTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture;

    /**
     * @ORM\OneToMany(targetEntity=Tournament::class, mappedBy="stadium", orphanRemoval=true)
     */
    private $tournaments;

    /**
     * @ORM\OneToMany(targetEntity=Club::class, mappedBy="stadium", orphanRemoval=true)
     */
    private $clubs;

    public function __construct()
    {
        $this->tournaments = new ArrayCollection();
        $this->clubs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection|Tournament[]
     */
    public function getTournaments(): Collection
    {
        return $this->tournaments;
    }

    public function addTournament(Tournament $tournament): self
    {
        if (!$this->tournaments->contains($tournament)) {
            $this->tournaments[] = $tournament;
            $tournament->setStadium($this);
        }

        return $this;
    }

    public function removeTournament(Tournament $tournament): self
    {
        if ($this->tournaments->removeElement($tournament)) {
            // set the owning side to null (unless already changed)
            if ($tournament->getStadium() === $this) {
                $tournament->setStadium(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Club[]
     */
    public function getClubs(): Collection
    {
        return $this->clubs;
    }

    public function addClub(Club $club): self
    {
        if (!$this->clubs->contains($club)) {
            $this->clubs[] = $club;
            $club->setStadium($this);
        }

        return $this;
    }

    public function removeClub(Club $club): self
    {
        if ($this->clubs->removeElement($club)) {
            // set the owning side to null (unless already changed)
            if ($club->getStadium() === $this) {
                $club->setStadium(null);
            }
        }

        return $this;
    }
}
