<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Commission;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/posts')]
class PostController extends AbstractController
{
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
}
