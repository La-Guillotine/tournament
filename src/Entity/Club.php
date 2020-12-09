<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\League;
use App\Entity\Stadium;
use App\Entity\Inscription;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClubRepository;
use App\Entity\Traits\AddressTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=ClubRepository::class)
 */
class Club
{
    use TimestampableTrait;
    use AddressTrait;
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
     * @ORM\Column(type="string", length=4)
     */
    private $acronym;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @ORM\OneToOne(targetEntity=Stadium::class, inversedBy="club", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $stadium;

    /**
     * @ORM\ManyToOne(targetEntity=League::class, inversedBy="clubs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $league;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="club", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $secretary;

    /**
     * @ORM\OneToMany(targetEntity=Inscription::class, mappedBy="club", orphanRemoval=true)
     */
    private $inscriptions;

    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
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

    public function getAcronym(): ?string
    {
        return $this->acronym;
    }

    public function setAcronym(string $acronym): self
    {
        $this->acronym = $acronym;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getStadium(): ?Stadium
    {
        return $this->stadium;
    }

    public function setStadium(Stadium $stadium): self
    {
        $this->stadium = $stadium;

        return $this;
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

    public function getSecretary(): ?User
    {
        return $this->secretary;
    }

    public function setSecretary(User $secretary): self
    {
        $this->secretary = $secretary;

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
            $inscription->setClub($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getClub() === $this) {
                $inscription->setClub(null);
            }
        }

        return $this;
    }
}
