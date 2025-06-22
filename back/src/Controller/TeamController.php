<?php

namespace App\Controller;

use App\Entity\Team;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/teams')]
class TeamController extends AbstractController
{
	private EntityManagerInterface $entityManager;
	private TeamRepository $teamRepository;
	private UserRepository $userRepository;
	private SerializerInterface $serializer;
	private ValidatorInterface $validator;

	public function __construct(
		EntityManagerInterface $entityManager,
		TeamRepository $teamRepository,
		UserRepository $userRepository,
		SerializerInterface $serializer,
		ValidatorInterface $validator
	) {
		$this->entityManager = $entityManager;
		$this->teamRepository = $teamRepository;
		$this->userRepository = $userRepository;
		$this->serializer = $serializer;
		$this->validator = $validator;
	}

	#[Route('', methods: ['GET'])]
	#[IsGranted('ROLE_USER')]
	public function index(): JsonResponse
	{
		$user = $this->getUser();
		$teams = $this->teamRepository->findBy(['user' => $user]);

		$data = $this->serializer->serialize($teams, 'json', ['groups' => ['team:read']]);

		return new JsonResponse($data, Response::HTTP_OK, [], true);
	}

	#[Route('/{id}', methods: ['GET'])]
	#[IsGranted('ROLE_USER')]
	public function show(int $id): JsonResponse
	{
		$user = $this->getUser();
		$team = $this->teamRepository->findOneBy(['id' => $id, 'user' => $user]);

		if (!$team) {
			return new JsonResponse(['message' => 'Team not found'], Response::HTTP_NOT_FOUND);
		}

		$data = $this->serializer->serialize($team, 'json', ['groups' => ['team:read']]);

		return new JsonResponse($data, Response::HTTP_OK, [], true);
	}

	#[Route('', methods: ['POST'])]
	#[IsGranted('ROLE_USER')]
	public function create(Request $request): JsonResponse
	{
		$user = $this->getUser();

		// Handle file upload
		$logoFile = $request->files->get('logo');
		$logoPath = null;

		if ($logoFile) {
			$uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/logos/';
			if (!is_dir($uploadDir)) {
				mkdir($uploadDir, 0777, true);
			}

			$filename = uniqid() . '.' . $logoFile->getClientOriginalExtension();
			$logoFile->move($uploadDir, $filename);
			$logoPath = '/uploads/logos/' . $filename;
		}

		$team = new Team();
		$team->setUser($user);
		$team->setName($request->request->get('name'));
		$team->setCategory($request->request->get('category'));
		$team->setLogo($logoPath);

		// Validate
		$errors = $this->validator->validate($team);
		if (count($errors) > 0) {
			$errorMessages = [];
			foreach ($errors as $error) {
				$errorMessages[] = $error->getMessage();
			}
			return new JsonResponse(['message' => 'Validation failed', 'errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
		}

		$this->entityManager->persist($team);
		$this->entityManager->flush();

		$data = $this->serializer->serialize($team, 'json', ['groups' => ['team:read']]);

		return new JsonResponse($data, Response::HTTP_CREATED, [], true);
	}

	#[Route('/{id}', methods: ['PUT'])]
	#[IsGranted('ROLE_USER')]
	public function update(Request $request, int $id): JsonResponse
	{
		$user = $this->getUser();
		$team = $this->teamRepository->findOneBy(['id' => $id, 'user' => $user]);

		if (!$team) {
			return new JsonResponse(['message' => 'Team not found'], Response::HTTP_NOT_FOUND);
		}

		// Handle file upload
		$logoFile = $request->files->get('logo');
		if ($logoFile) {
			$uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/logos/';
			if (!is_dir($uploadDir)) {
				mkdir($uploadDir, 0777, true);
			}

			$filename = uniqid() . '.' . $logoFile->getClientOriginalExtension();
			$logoFile->move($uploadDir, $filename);
			$logoPath = '/uploads/logos/' . $filename;
			$team->setLogo($logoPath);
		}

		if ($request->request->has('name')) {
			$team->setName($request->request->get('name'));
		}

		if ($request->request->has('category')) {
			$team->setCategory($request->request->get('category'));
		}

		// Validate
		$errors = $this->validator->validate($team);
		if (count($errors) > 0) {
			$errorMessages = [];
			foreach ($errors as $error) {
				$errorMessages[] = $error->getMessage();
			}
			return new JsonResponse(['message' => 'Validation failed', 'errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
		}

		$this->entityManager->flush();

		$data = $this->serializer->serialize($team, 'json', ['groups' => ['team:read']]);

		return new JsonResponse($data, Response::HTTP_OK, [], true);
	}

	#[Route('/{id}', methods: ['DELETE'])]
	#[IsGranted('ROLE_USER')]
	public function delete(int $id): JsonResponse
	{
		$user = $this->getUser();
		$team = $this->teamRepository->findOneBy(['id' => $id, 'user' => $user]);

		if (!$team) {
			return new JsonResponse(['message' => 'Team not found'], Response::HTTP_NOT_FOUND);
		}

		$this->entityManager->remove($team);
		$this->entityManager->flush();

		return new JsonResponse(['message' => 'Team deleted successfully'], Response::HTTP_OK);
	}
}
