<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Notification;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/notification')]
class NotificationController extends AbstractController
{
    // Page d'index des notifications
    #[Route('/', name: 'notification_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('notification/index.html.twig', [
            'controller_name' => 'NotificationController',
        ]);
    }

    // API pour récupérer les notifications de l'utilisateur connecté
    #[Route('/api', name: 'notification_api_index', methods: ['GET'])]
    public function apiIndex(EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Access Denied'], Response::HTTP_FORBIDDEN);
        }

        $notifications = $entityManager->getRepository(Notification::class)->findBy(['user' => $user]);

        $data = array_map(function ($notification) {
            return [
                'id' => $notification->getId(),
                'message' => $notification->getMessage(),
                'isRead' => $notification->isRead(),
                'post' => $notification->getPost() ? $notification->getPost()->getId() : null,
                'createdAt' => $notification->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }, $notifications);

        return new JsonResponse($data);
    }

    // Affiche une notification spécifique
    #[Route('/show/{id}', name: 'notification_show', methods: ['GET'])]
    public function show(Notification $notification): Response
    {
        return $this->render('notification/show.html.twig', [
            'notification' => $notification,
        ]);
    }

    // API pour marquer une notification comme lue
    #[Route('/api/{id}/mark-read', name: 'notification_mark_read', methods: ['POST'])]
    public function markAsRead(Notification $notification, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        if (!$user || $notification->getUser() !== $user) {
            return new JsonResponse(['error' => 'Access Denied'], Response::HTTP_FORBIDDEN);
        }

        $notification->setRead(true);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Notification marked as read']);
    }

    // Création d'une nouvelle notification
    #[Route('/new', name: 'notification_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $notification = new Notification();
        $form = $this->createForm(NotificationType::class, $notification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            if (!$user) {
                throw new AccessDeniedException('You must be logged in to create a notification.');
            }

            $notification->setUser($user);
            $notification->setCreatedAt(new \DateTime());
            $notification->setRead(false);

            $entityManager->persist($notification);
            $entityManager->flush();

            return $this->redirectToRoute('notification_index');
        }

        return $this->render('notification/new.html.twig', [
            'notification' => $notification,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/api/create', name: 'notification_create', methods: ['POST'])]
    public function createNotification(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Access Denied'], Response::HTTP_FORBIDDEN);
        }

        $data = json_decode($request->getContent(), true);
        $message = $data['message'] ?? null;

        if (!$message) {
            return new JsonResponse(['error' => 'Message is required'], Response::HTTP_BAD_REQUEST);
        }

        $notification = new Notification();
        $notification->setUser($user);
        $notification->setMessage($message);
        $notification->setCreatedAt(new \DateTime());
        $notification->setRead(false);

        $entityManager->persist($notification);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Notification created successfully'], Response::HTTP_CREATED);
    }
}