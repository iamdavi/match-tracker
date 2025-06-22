<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(type: Types::DATETIME_MUTABLE)]
	private ?\DateTimeInterface $dateTime = null;

	#[ORM\Column(length: 255)]
	private ?string $location = null;

	#[ORM\Column(nullable: true)]
	private ?int $resultLocal = null;

	#[ORM\Column(nullable: true)]
	private ?int $resultVisitor = null;

	#[ORM\Column(type: Types::TEXT, nullable: true)]
	private ?string $notes = null;

	#[ORM\ManyToOne(inversedBy: 'localMatches')]
	private ?Team $localTeam = null;

	#[ORM\ManyToOne(inversedBy: 'visitorMatches')]
	private ?Team $visitorTeam = null;

	#[ORM\OneToMany(mappedBy: 'game', targetEntity: Event::class, orphanRemoval: true)]
	private Collection $events;

	public function __construct()
	{
		$this->events = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getDateTime(): ?\DateTimeInterface
	{
		return $this->dateTime;
	}

	public function setDateTime(\DateTimeInterface $dateTime): static
	{
		$this->dateTime = $dateTime;

		return $this;
	}

	public function getLocation(): ?string
	{
		return $this->location;
	}

	public function setLocation(string $location): static
	{
		$this->location = $location;

		return $this;
	}

	public function getResultLocal(): ?int
	{
		return $this->resultLocal;
	}

	public function setResultLocal(?int $resultLocal): static
	{
		$this->resultLocal = $resultLocal;

		return $this;
	}

	public function getResultVisitor(): ?int
	{
		return $this->resultVisitor;
	}

	public function setResultVisitor(?int $resultVisitor): static
	{
		$this->resultVisitor = $resultVisitor;

		return $this;
	}

	public function getNotes(): ?string
	{
		return $this->notes;
	}

	public function setNotes(?string $notes): static
	{
		$this->notes = $notes;

		return $this;
	}

	public function getLocalTeam(): ?Team
	{
		return $this->localTeam;
	}

	public function setLocalTeam(?Team $localTeam): static
	{
		$this->localTeam = $localTeam;

		return $this;
	}

	public function getVisitorTeam(): ?Team
	{
		return $this->visitorTeam;
	}

	public function setVisitorTeam(?Team $visitorTeam): static
	{
		$this->visitorTeam = $visitorTeam;

		return $this;
	}

	/**
	 * @return Collection<int, Event>
	 */
	public function getEvents(): Collection
	{
		return $this->events;
	}

	public function addEvent(Event $event): static
	{
		if (!$this->events->contains($event)) {
			$this->events->add($event);
			$event->setGame($this);
		}

		return $this;
	}

	public function removeEvent(Event $event): static
	{
		if ($this->events->removeElement($event)) {
			// set the owning side to null (unless already changed)
			if ($event->getGame() === $this) {
				$event->setGame(null);
			}
		}

		return $this;
	}
}
