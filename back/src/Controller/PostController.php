<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/new', name: 'post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        $user = $this->getUser();
        $commission = $post->getCommission();

        // Vérifiez si l'utilisateur a le privilège de créer des posts dans cette commission
        $hasPrivilege = false;
        foreach ($user->getPrivileges() as $privilege) {
            if ($privilege->getCommission() === $commission && $privilege->getRole() === 'CAN_CREATE_POST') {
                $hasPrivilege = true;
                break;
            }
        }

        if (!$hasPrivilege) {
            throw new AccessDeniedException('You do not have permission to create a post in this commission.');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($user);
            $post->setCreatedAt($commission->getCreatedAt());

            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }
}
