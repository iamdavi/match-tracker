<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
class Role
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(length: 255)]
	private ?string $name = null;

	#[ORM\OneToMany(mappedBy: 'role', targetEntity: UserTeamRole::class, orphanRemoval: true)]
	private Collection $userTeamRoles;

	public function __construct()
	{
		$this->userTeamRoles = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setName(string $name): static
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return Collection<int, UserTeamRole>
	 */
	public function getUserTeamRoles(): Collection
	{
		return $this->userTeamRoles;
	}

	public function addUserTeamRole(UserTeamRole $userTeamRole): static
	{
		if (!$this->userTeamRoles->contains($userTeamRole)) {
			$this->userTeamRoles->add($userTeamRole);
			$userTeamRole->setRole($this);
		}

		return $this;
	}

	public function removeUserTeamRole(UserTeamRole $userTeamRole): static
	{
		if ($this->userTeamRoles->removeElement($userTeamRole)) {
			// set the owning side to null (unless already changed)
			if ($userTeamRole->getRole() === $this) {
				$userTeamRole->setRole(null);
			}
		}

		return $this;
	}
}
