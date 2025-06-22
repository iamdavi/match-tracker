<?php

namespace App\Entity;

use App\Repository\UserTeamRoleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserTeamRoleRepository::class)]
class UserTeamRole
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\ManyToOne(inversedBy: 'userTeamRoles')]
	#[ORM\JoinColumn(nullable: false)]
	private ?User $user = null;

	#[ORM\ManyToOne(inversedBy: 'userTeamRoles')]
	#[ORM\JoinColumn(nullable: false)]
	private ?Team $team = null;

	#[ORM\ManyToOne(inversedBy: 'userTeamRoles')]
	#[ORM\JoinColumn(nullable: false)]
	private ?Role $role = null;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getUser(): ?User
	{
		return $this->user;
	}

	public function setUser(?User $user): static
	{
		$this->user = $user;

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

	public function getRole(): ?Role
	{
		return $this->role;
	}

	public function setRole(?Role $role): static
	{
		$this->role = $role;

		return $this;
	}
}
