<?php

namespace App\Entity;

use App\Entity\League;
use App\Entity\Stadium;
use App\Entity\Inscription;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=TournamentRepository::class)
 */
class Tournament
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=League::class, inversedBy="tournaments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $league;

    /**
     * @ORM\ManyToOne(targetEntity=Stadium::class, inversedBy="tournaments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $stadium;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start_date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end_date;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbMaxTeam;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $ageCategory;

    /**
     * @ORM\OneToMany(targetEntity=Inscription::class, mappedBy="tournament", orphanRemoval=true)
     */
    private $inscriptions;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $title;

    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLeague(): ?League
    {
        return $this->league;
    }

    public function setLeague(?League $league): self
    {
        $this->league = $league;

        return $this;
    }

    public function getStadium(): ?Stadium
    {
        return $this->stadium;
    }

    public function setStadium(?Stadium $stadium): self
    {
        $this->stadium = $stadium;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getNbMaxTeam(): ?int
    {
        return $this->nbMaxTeam;
    }

    public function setNbMaxTeam(int $nbMaxTeam): self
    {
        $this->nbMaxTeam = $nbMaxTeam;

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

    public function getAgeCategory(): ?string
    {
        return $this->ageCategory;
    }

    public function setAgeCategory(?string $ageCategory): self
    {
        $this->ageCategory = $ageCategory;

        return $this;
    }

    /**
     * @return Collection|Inscription[]
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
            $inscription->setTournament($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getTournament() === $this) {
                $inscription->setTournament(null);
            }
        }

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
