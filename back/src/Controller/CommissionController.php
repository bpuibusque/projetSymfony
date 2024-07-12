<?php

namespace App\Controller;

use App\Entity\Commission;
use App\Entity\UserCommissionSubscription;
use App\Form\CommissionType;
use App\Repository\CommissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commissions')]
class CommissionController extends AbstractController
{
    #[Route('/', name: 'commission_index', methods: ['GET'])]
    public function index(CommissionRepository $commissionRepository): Response
    {
        return $this->render('commission/index.html.twig', [
            'commissions' => $commissionRepository->findAll(),
        ]);
    }

    #[Route('/api', name: 'api_commission_index', methods: ['GET'])]
    public function apiIndex(CommissionRepository $commissionRepository): JsonResponse
    {
        $commissions = $commissionRepository->findAll();
        $data = [];

        foreach ($commissions as $commission) {
            $data[] = [
                'id' => $commission->getId(),
                'name' => $commission->getName(),
                'description' => $commission->getDescription(),
                'createdAt' => $commission->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/api/{id}/posts', name: 'api_commission_posts', methods: ['GET'])]
    public function apiPosts(Commission $commission): JsonResponse
    {
        $posts = $commission->getPosts();
        $data = [];

        foreach ($posts as $post) {
            $data[] = [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'createdAt' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
                'user' => [
                    'email' => $post->getUser()->getEmail()
                ]
            ];
        }

        return new JsonResponse([
            'commissionName' => $commission->getName(),
            'posts' => $data
        ]);
    }

    #[Route('/new', name: 'commission_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commission = new Commission();
        $form = $this->createForm(CommissionType::class, $commission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commission);
            $entityManager->flush();

            return $this->redirectToRoute('commission_index');
        }

        return $this->render('commission/new.html.twig', [
            'commission' => $commission,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'commission_show', methods: ['GET'])]
    public function show(Commission $commission): Response
    {
        return $this->render('commission/show.html.twig', [
            'commission' => $commission,
            'posts' => $commission->getPosts(),
        ]);
    }

    #[Route('/{id}/edit', name: 'commission_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commission $commission, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommissionType::class, $commission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('commission_index');
        }

        return $this->render('commission/edit.html.twig', [
            'commission' => $commission,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'commission_delete', methods: ['POST'])]
    public function delete(Request $request, Commission $commission, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commission->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commission);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commission_index');
    }

    #[Route('/{id}/subscribe', name: 'commission_subscribe', methods: ['POST'])]
    public function subscribe(Request $request, Commission $commission, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to subscribe to a commission.');
        }

        $subscription = new UserCommissionSubscription();
        $subscription->setUser($user);
        $subscription->setCommission($commission);

        $entityManager->persist($subscription);
        $entityManager->flush();

        return $this->redirectToRoute('commission_show', ['id' => $commission->getId()]);
    }
}
