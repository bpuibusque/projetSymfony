<?php

namespace App\Controller;

use App\Entity\PrivateMessage;
use App\Entity\User;
use App\Form\PrivateMessageType;
use App\Repository\PrivateMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/private_message')]
class PrivateMessageController extends AbstractController
{
    // Affiche les messages reçus
    #[Route('/received', name: 'private_message_received', methods: ['GET'])]
    public function receivedMessages(Request $request, PrivateMessageRepository $privateMessageRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $userID = $request->query->get('userID');
         // Vérifiez si l'userID est valide
        if (!$userID || !is_numeric($userID)) {
            return new JsonResponse(['error' => 'Invalid or missing userID'], Response::HTTP_BAD_REQUEST);
        }
        $user = $entityManager->getRepository(User::class)->find($userID);
        // Vérifiez si l'utilisateur existe
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        // Récupérer l'utilisateur à partir de l'userID
        $user = $entityManager->getRepository(User::class)->find($userID);

        $messages = $privateMessageRepository->findBy(['receiver' => $user]);

        $data = array_map(function ($message) {
            return [
                'id' => $message->getId(),
                'content' => $message->getContent(),
                'sender' => $message->getSender()->getEmail(),
                'createdAt' => $message->getSendAt()->format('Y-m-d H:i:s'),
            ];
        }, $messages);

        return new JsonResponse($data);
    }

    // Affiche les messages envoyés
    #[Route('/sent', name: 'private_message_sent', methods: ['GET'])]
    public function sentMessages(Request $request, PrivateMessageRepository $privateMessageRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $userID = $request->query->get('userID');
         // Vérifiez si l'userID est valide
        if (!$userID || !is_numeric($userID)) {
            return new JsonResponse(['error' => 'Invalid or missing userID'], Response::HTTP_BAD_REQUEST);
        }
        $user = $entityManager->getRepository(User::class)->find($userID);
        // Vérifiez si l'utilisateur existe
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        // Récupérer l'utilisateur à partir de l'userID
        $user = $entityManager->getRepository(User::class)->find($userID);

        $messages = $privateMessageRepository->findBy(['sender' => $user]);

        $data = array_map(function ($message) {
            return [
                'id' => $message->getId(),
                'content' => $message->getContent(),
                'recipient' => $message->getReceiver()->getEmail(),
                'createdAt' => $message->getSendAt()->format('Y-m-d H:i:s'),
            ];
        }, $messages);

        return new JsonResponse($data);
    }

    #[Route('/api/new', name: 'private_message_new', methods: ['POST'])]
    public function sendPrivateMessage(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $userID = $data['userID'] ?? null;

        if (!$userID || !is_numeric($userID)) {
            return new JsonResponse(['error' => 'Invalid or missing userID'], Response::HTTP_BAD_REQUEST);
        }

        $user = $entityManager->getRepository(User::class)->find($userID);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated'], 403);
        }

        $recipientId = $data['recipientId'] ?? null;
        $content = $data['content'] ?? null;

        if (!$recipientId || !$content) {
            return new JsonResponse(['error' => 'Recipient and content are required'], 400);
        }

        $recipient = $entityManager->getRepository(User::class)->find($recipientId);
        if (!$recipient) {
            return new JsonResponse(['error' => 'Recipient not found'], 404);
        }

        $message = new PrivateMessage();
        $message->setSender($user);
        $message->setReceiver($recipient);
        $message->setContent($content);
        $message->setSendAt(new \DateTime());

        $entityManager->persist($message);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Message sent successfully'], 201);
    }

}
