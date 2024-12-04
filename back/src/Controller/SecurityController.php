<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\JsonAuthenticator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
class SecurityController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $doctrine,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function apiLogin(Request $request, UserAuthenticatorInterface $userAuthenticator, JsonAuthenticator $authenticator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        // Vérifiez les entrées
        if (!$email || !$password) {
            return new JsonResponse(['error' => 'Invalid credentials'], 400);
        }

        // Récupérer l'utilisateur via le repository
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => $email]);

        // Vérifiez l'utilisateur et le mot de passe
        if (!$user || !$this->passwordHasher->isPasswordValid($user, $password)) {
            return new JsonResponse(['error' => 'Invalid credentials'], 401);
        }

        // Générer un token (simple exemple, à remplacer par JWT ou autre)
        $token = bin2hex(random_bytes(32));

        // Retournez une réponse JSON avec l'utilisateur
        return new JsonResponse([
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ],
        ]);
    }

    #[Route('/api/user', name: 'api_user', methods: ['GET'])]
    public function getUserInfo(?UserInterface $user): JsonResponse
    {
        if (!$user) {
            return new JsonResponse(['error' => 'User is not authenticated'], 403);
        }
        if (!$user instanceof \App\Entity\User) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }
        // Utilisez directement l'objet UserInterface injecté
        return new JsonResponse([
            'id' => $user->getId(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);
    }

    #[Route('/users/api/users', name: 'api_users', methods: ['GET'])]
    public function fetchUsers(EntityManagerInterface $entityManager): JsonResponse
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        $data = array_map(function ($users) {
            return [
                'id' => $users->getId(),
                'email' => $users->getEmail(),
            ];
        }, $users);

        return new JsonResponse($data);
    }
}
