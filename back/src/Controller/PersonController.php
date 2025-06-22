<?php

namespace App\Controller;

use App\Entity\Person;
use App\Entity\Team;
use App\Repository\PersonRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/players')]
class PersonController extends AbstractController
{
	private EntityManagerInterface $entityManager;
	private PersonRepository $personRepository;
	private TeamRepository $teamRepository;

	public function __construct(
		EntityManagerInterface $entityManager,
		PersonRepository $personRepository,
		TeamRepository $teamRepository
	) {
		$this->entityManager = $entityManager;
		$this->personRepository = $personRepository;
		$this->teamRepository = $teamRepository;
	}

	#[Route('', methods: ['GET'])]
	#[IsGranted('ROLE_USER')]
	public function index(): JsonResponse
	{
		$user = $this->getUser();

		// Get all teams for the user
		$teams = $this->teamRepository->findBy(['user' => $user]);
		$teamIds = array_map(function ($team) {
			return $team->getId();
		}, $teams);

		// Get all players from user's teams
		$players = $this->personRepository->createQueryBuilder('p')
			->join('p.team', 't')
			->where('t.id IN (:teamIds)')
			->setParameter('teamIds', $teamIds)
			->getQuery()
			->getResult();

		$data = [];
		foreach ($players as $player) {
			$data[] = [
				'id' => $player->getId(),
				'firstName' => $player->getFirstName(),
				'lastName' => $player->getLastName(),
				'position' => $player->getPosition(),
				'number' => $player->getNumber(),
				'birthDate' => $player->getBirthDate() ? $player->getBirthDate()->format('Y-m-d') : null,
				'role' => $player->getRole(),
				'team' => [
					'id' => $player->getTeam()->getId(),
					'name' => $player->getTeam()->getName()
				]
			];
		}

		return new JsonResponse($data, Response::HTTP_OK);
	}

	#[Route('/{id}', methods: ['GET'])]
	#[IsGranted('ROLE_USER')]
	public function show(int $id): JsonResponse
	{
		$user = $this->getUser();

		$player = $this->personRepository->createQueryBuilder('p')
			->join('p.team', 't')
			->where('p.id = :id')
			->andWhere('t.user = :user')
			->setParameter('id', $id)
			->setParameter('user', $user)
			->getQuery()
			->getOneOrNullResult();

		if (!$player) {
			return new JsonResponse(['message' => 'Player not found'], Response::HTTP_NOT_FOUND);
		}

		$data = [
			'id' => $player->getId(),
			'firstName' => $player->getFirstName(),
			'lastName' => $player->getLastName(),
			'position' => $player->getPosition(),
			'number' => $player->getNumber(),
			'birthDate' => $player->getBirthDate() ? $player->getBirthDate()->format('Y-m-d') : null,
			'role' => $player->getRole(),
			'team' => [
				'id' => $player->getTeam()->getId(),
				'name' => $player->getTeam()->getName()
			]
		];

		return new JsonResponse($data, Response::HTTP_OK);
	}

	#[Route('', methods: ['POST'])]
	#[IsGranted('ROLE_USER')]
	public function create(Request $request): JsonResponse
	{
		$user = $this->getUser();
		$data = json_decode($request->getContent(), true);

		// Validate required fields
		if (!isset($data['firstName']) || !isset($data['lastName']) || !isset($data['role']) || !isset($data['teamId'])) {
			return new JsonResponse(['message' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
		}

		// Check if team belongs to user
		$team = $this->teamRepository->findOneBy(['id' => $data['teamId'], 'user' => $user]);
		if (!$team) {
			return new JsonResponse(['message' => 'Team not found or access denied'], Response::HTTP_NOT_FOUND);
		}

		$player = new Person();
		$player->setFirstName($data['firstName']);
		$player->setLastName($data['lastName']);
		$player->setRole($data['role']);
		$player->setTeam($team);

		// Optional fields
		if (isset($data['position'])) {
			$player->setPosition($data['position']);
		}
		if (isset($data['number'])) {
			$player->setNumber($data['number']);
		}
		if (isset($data['birthDate'])) {
			$player->setBirthDate(new \DateTime($data['birthDate']));
		}

		$this->entityManager->persist($player);
		$this->entityManager->flush();

		$responseData = [
			'id' => $player->getId(),
			'firstName' => $player->getFirstName(),
			'lastName' => $player->getLastName(),
			'position' => $player->getPosition(),
			'number' => $player->getNumber(),
			'birthDate' => $player->getBirthDate() ? $player->getBirthDate()->format('Y-m-d') : null,
			'role' => $player->getRole(),
			'team' => [
				'id' => $player->getTeam()->getId(),
				'name' => $player->getTeam()->getName()
			]
		];

		return new JsonResponse($responseData, Response::HTTP_CREATED);
	}

	#[Route('/{id}', methods: ['PUT'])]
	#[IsGranted('ROLE_USER')]
	public function update(Request $request, int $id): JsonResponse
	{
		$user = $this->getUser();
		$data = json_decode($request->getContent(), true);

		$player = $this->personRepository->createQueryBuilder('p')
			->join('p.team', 't')
			->where('p.id = :id')
			->andWhere('t.user = :user')
			->setParameter('id', $id)
			->setParameter('user', $user)
			->getQuery()
			->getOneOrNullResult();

		if (!$player) {
			return new JsonResponse(['message' => 'Player not found'], Response::HTTP_NOT_FOUND);
		}

		// Update fields if provided
		if (isset($data['firstName'])) {
			$player->setFirstName($data['firstName']);
		}
		if (isset($data['lastName'])) {
			$player->setLastName($data['lastName']);
		}
		if (isset($data['position'])) {
			$player->setPosition($data['position']);
		}
		if (isset($data['number'])) {
			$player->setNumber($data['number']);
		}
		if (isset($data['birthDate'])) {
			$player->setBirthDate(new \DateTime($data['birthDate']));
		}
		if (isset($data['role'])) {
			$player->setRole($data['role']);
		}
		if (isset($data['teamId'])) {
			// Check if new team belongs to user
			$newTeam = $this->teamRepository->findOneBy(['id' => $data['teamId'], 'user' => $user]);
			if (!$newTeam) {
				return new JsonResponse(['message' => 'Team not found or access denied'], Response::HTTP_NOT_FOUND);
			}
			$player->setTeam($newTeam);
		}

		$this->entityManager->flush();

		$responseData = [
			'id' => $player->getId(),
			'firstName' => $player->getFirstName(),
			'lastName' => $player->getLastName(),
			'position' => $player->getPosition(),
			'number' => $player->getNumber(),
			'birthDate' => $player->getBirthDate() ? $player->getBirthDate()->format('Y-m-d') : null,
			'role' => $player->getRole(),
			'team' => [
				'id' => $player->getTeam()->getId(),
				'name' => $player->getTeam()->getName()
			]
		];

		return new JsonResponse($responseData, Response::HTTP_OK);
	}

	#[Route('/{id}', methods: ['DELETE'])]
	#[IsGranted('ROLE_USER')]
	public function delete(int $id): JsonResponse
	{
		$user = $this->getUser();

		$player = $this->personRepository->createQueryBuilder('p')
			->join('p.team', 't')
			->where('p.id = :id')
			->andWhere('t.user = :user')
			->setParameter('id', $id)
			->setParameter('user', $user)
			->getQuery()
			->getOneOrNullResult();

		if (!$player) {
			return new JsonResponse(['message' => 'Player not found'], Response::HTTP_NOT_FOUND);
		}

		$this->entityManager->remove($player);
		$this->entityManager->flush();

		return new JsonResponse(['message' => 'Player deleted successfully'], Response::HTTP_OK);
	}
}
