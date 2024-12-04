<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Commission;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/posts')]
class PostController extends AbstractController
{
    // Form-based post creation
    #[Route('/new', name: 'post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to create a post.');
        }

        $post = new Post();
        $post->setUser($user);

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($post->getCommission() === null) {
                // Handle the post for the general commission
                $generalCommission = $entityManager->getRepository(Commission::class)->findOneBy(['name' => 'General']);
                $post->setCommission($generalCommission);
            }

            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('commission_show', ['id' => $post->getCommission()->getId()]);
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    // API-based post creation
    #[Route('/api/new', name: 'api_post_new', methods: ['POST'])]
    public function apiNew(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Access Denied'], Response::HTTP_FORBIDDEN);
        }

        $data = json_decode($request->getContent(), true);

        // Vérifiez si la commission existe
        $commission = $entityManager->getRepository(Commission::class)->find($data['commission']['id'] ?? null);
        if (!$commission) {
            return new JsonResponse(['error' => 'Commission not found'], Response::HTTP_NOT_FOUND);
        }

        $post = new Post();
        $post->setUser($user);
        $post->setTitle($data['title'] ?? '');
        $post->setContent($data['content'] ?? '');
        $post->setCommission($commission);

        $entityManager->persist($post);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Post created successfully'], Response::HTTP_CREATED);
    }
}
