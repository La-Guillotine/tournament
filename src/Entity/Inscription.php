<?php

namespace App\Entity;

use App\Entity\Club;
use App\Entity\Tournament;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\InscriptionRepository;

/**
 * @ORM\Entity(repositoryClass=InscriptionRepository::class)
 */
class Inscription
{
    use TimestampableTrait;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $status;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Tournament::class, inversedBy="inscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournament;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="inscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $club;

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): self
    {
        $this->tournament = $tournament;

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): self
    {
        $this->club = $club;

        return $this;
    }
}
