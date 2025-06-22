<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\ManyToOne(inversedBy: 'events')]
	#[ORM\JoinColumn(nullable: false)]
	private ?Game $game = null;

	#[ORM\Column(length: 255)]
	private ?string $type = null;

	#[ORM\Column]
	private ?int $minute = null;

	#[ORM\Column(type: Types::TEXT, nullable: true)]
	private ?string $description = null;

	#[ORM\Column(length: 255, nullable: true)]
	private ?string $period = null;

	#[ORM\ManyToOne(inversedBy: 'events')]
	private ?Person $person = null;

	#[ORM\ManyToOne(inversedBy: 'events')]
	private ?Team $team = null;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getGame(): ?Game
	{
		return $this->game;
	}

	public function setGame(?Game $game): static
	{
		$this->game = $game;

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

	public function getMinute(): ?int
	{
		return $this->minute;
	}

	public function setMinute(int $minute): static
	{
		$this->minute = $minute;

		return $this;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(?string $description): static
	{
		$this->description = $description;

		return $this;
	}

	public function getPeriod(): ?string
	{
		return $this->period;
	}

	public function setPeriod(?string $period): static
	{
		$this->period = $period;

		return $this;
	}

	public function getPerson(): ?Person
	{
		return $this->person;
	}

	public function setPerson(?Person $person): static
	{
		$this->person = $person;

		return $this;
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
}
