<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
class Person
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\ManyToOne(inversedBy: 'persons')]
	#[ORM\JoinColumn(nullable: false)]
	private ?Team $team = null;

	#[ORM\Column(length: 255)]
	private ?string $firstName = null;

	#[ORM\Column(length: 255)]
	private ?string $lastName = null;

	#[ORM\Column(length: 255, nullable: true)]
	private ?string $position = null;

	#[ORM\Column(nullable: true)]
	private ?int $number = null;

	#[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
	private ?\DateTimeInterface $birthDate = null;

	#[ORM\Column(length: 255)]
	private ?string $role = null;

	#[ORM\OneToMany(mappedBy: 'person', targetEntity: Event::class)]
	private Collection $events;

	public function __construct()
	{
		$this->events = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getTeam(): ?Team
	{
		return $this->team;
	}

	public function setTeam(?Team $team): static
	{
		$this->team = $team;

		return $this;
	}

	public function getFirstName(): ?string
	{
		return $this->firstName;
	}

	public function setFirstName(string $firstName): static
	{
		$this->firstName = $firstName;

		return $this;
	}

	public function getLastName(): ?string
	{
		return $this->lastName;
	}

	public function setLastName(string $lastName): static
	{
		$this->lastName = $lastName;

		return $this;
	}

	public function getPosition(): ?string
	{
		return $this->position;
	}

	public function setPosition(?string $position): static
	{
		$this->position = $position;

		return $this;
	}

	public function getNumber(): ?int
	{
		return $this->number;
	}

	public function setNumber(?int $number): static
	{
		$this->number = $number;

		return $this;
	}

	public function getBirthDate(): ?\DateTimeInterface
	{
		return $this->birthDate;
	}

	public function setBirthDate(?\DateTimeInterface $birthDate): static
	{
		$this->birthDate = $birthDate;

		return $this;
	}

	public function getRole(): ?string
	{
		return $this->role;
	}

	public function setRole(string $role): static
	{
		$this->role = $role;

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
			$event->setPerson($this);
		}

		return $this;
	}

	public function removeEvent(Event $event): static
	{
		if ($this->events->removeElement($event)) {
			// set the owning side to null (unless already changed)
			if ($event->getPerson() === $this) {
				$event->setPerson(null);
			}
		}

		return $this;
	}
}
