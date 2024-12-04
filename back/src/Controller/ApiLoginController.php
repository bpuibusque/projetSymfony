<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(
        #[CurrentUser] ?UserInterface $user
    ): JsonResponse {
        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'Invalid credentials'], 401);
        }

        return new JsonResponse([
            'success' => true,
            'userIdentifier' => $user->getUserIdentifier(),
        ]);
    }
}
