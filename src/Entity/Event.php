<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $starttime;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $duration;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $lastInscriptionTime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $information;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\State", inversedBy="events")
     *
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", inversedBy="events")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Campus", inversedBy="events")
     */
    private $campus;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Participant", inversedBy="events")
     */
    private $participants;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Participant")
     */
    private $planner;

    /**
     * @ORM\Column(type="integer")
     */
    private $places;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
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

    public function getStarttime(): ?\DateTimeInterface
    {
        return $this->starttime;
    }

    public function setStarttime(\DateTimeInterface $starttime): self
    {
        $this->starttime = $starttime;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getLastInscriptionTime(): ?\DateTimeInterface
    {
        return $this->lastInscriptionTime;
    }

    public function setLastInscriptionTime(\DateTimeInterface $lastInscriptionTime): self
    {
        $this->lastInscriptionTime = $lastInscriptionTime;

        return $this;
    }

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(string $information): self
    {
        $this->information = $information;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection|Participant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
        }

        return $this;
    }

    public function getPlanner(): ?Participant
    {
        return $this->planner;
    }

    public function setPlanner(?Participant $planner): self
    {
        $this->planner = $planner;

        return $this;
    }

    public function getPlaces(): ?int
    {
        return $this->places;
    }

    public function setPlaces(int $places): self
    {
        $this->places = $places;

        return $this;
    }
}
