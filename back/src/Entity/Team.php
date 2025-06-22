<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\ManyToOne(inversedBy: 'teams')]
	#[ORM\JoinColumn(nullable: false)]
	private ?User $user = null;

	#[ORM\Column(type: 'text')]
	private ?string $name = null;

	#[ORM\Column(type: 'text', nullable: true)]
	private ?string $logo = null;

	#[ORM\Column(type: 'text')]
	private ?string $category = null;

	#[ORM\OneToMany(mappedBy: 'team', targetEntity: Person::class, orphanRemoval: true)]
	private Collection $persons;

	#[ORM\OneToMany(mappedBy: 'team', targetEntity: UserTeamRole::class, orphanRemoval: true)]
	private Collection $userTeamRoles;

	#[ORM\OneToMany(mappedBy: 'localTeam', targetEntity: Game::class)]
	private Collection $localMatches;

	#[ORM\OneToMany(mappedBy: 'visitorTeam', targetEntity: Game::class)]
	private Collection $visitorMatches;

	#[ORM\OneToMany(mappedBy: 'team', targetEntity: Event::class)]
	private Collection $events;

	public function __construct()
	{
		$this->persons = new ArrayCollection();
		$this->userTeamRoles = new ArrayCollection();
		$this->localMatches = new ArrayCollection();
		$this->visitorMatches = new ArrayCollection();
		$this->events = new ArrayCollection();
	}

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

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setName(string $name): static
	{
		$this->name = $name;

		return $this;
	}

	public function getLogo(): ?string
	{
		return $this->logo;
	}

	public function setLogo(?string $logo): static
	{
		$this->logo = $logo;

		return $this;
	}

	public function getCategory(): ?string
	{
		return $this->category;
	}

	public function setCategory(string $category): static
	{
		$this->category = $category;

		return $this;
	}

	/**
	 * @return Collection<int, Person>
	 */
	public function getPersons(): Collection
	{
		return $this->persons;
	}

	public function addPerson(Person $person): static
	{
		if (!$this->persons->contains($person)) {
			$this->persons->add($person);
			$person->setTeam($this);
		}

		return $this;
	}

	public function removePerson(Person $person): static
	{
		if ($this->persons->removeElement($person)) {
			// set the owning side to null (unless already changed)
			if ($person->getTeam() === $this) {
				$person->setTeam(null);
			}
		}

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
			$userTeamRole->setTeam($this);
		}

		return $this;
	}

	public function removeUserTeamRole(UserTeamRole $userTeamRole): static
	{
		if ($this->userTeamRoles->removeElement($userTeamRole)) {
			// set the owning side to null (unless already changed)
			if ($userTeamRole->getTeam() === $this) {
				$userTeamRole->setTeam(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection<int, Game>
	 */
	public function getLocalMatches(): Collection
	{
		return $this->localMatches;
	}

	public function addLocalMatch(Game $localMatch): static
	{
		if (!$this->localMatches->contains($localMatch)) {
			$this->localMatches->add($localMatch);
			$localMatch->setLocalTeam($this);
		}

		return $this;
	}

	public function removeLocalMatch(Game $localMatch): static
	{
		if ($this->localMatches->removeElement($localMatch)) {
			// set the owning side to null (unless already changed)
			if ($localMatch->getLocalTeam() === $this) {
				$localMatch->setLocalTeam(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection<int, Game>
	 */
	public function getVisitorMatches(): Collection
	{
		return $this->visitorMatches;
	}

	public function addVisitorMatch(Game $visitorMatch): static
	{
		if (!$this->visitorMatches->contains($visitorMatch)) {
			$this->visitorMatches->add($visitorMatch);
			$visitorMatch->setVisitorTeam($this);
		}

		return $this;
	}

	public function removeVisitorMatch(Game $visitorMatch): static
	{
		if ($this->visitorMatches->removeElement($visitorMatch)) {
			// set the owning side to null (unless already changed)
			if ($visitorMatch->getVisitorTeam() === $this) {
				$visitorMatch->setVisitorTeam(null);
			}
		}

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
			$event->setTeam($this);
		}

		return $this;
	}

	public function removeEvent(Event $event): static
	{
		if ($this->events->removeElement($event)) {
			// set the owning side to null (unless already changed)
			if ($event->getTeam() === $this) {
				$event->setTeam(null);
			}
		}

		return $this;
	}
}
