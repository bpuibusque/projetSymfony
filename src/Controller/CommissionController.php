<?php

namespace App\Controller;

use App\Entity\Commission;
use App\Form\CommissionType;
use App\Repository\CommissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commission')]
class CommissionController extends AbstractController
{
    #[Route('/', name: 'commission_index', methods: ['GET'])]
    public function index(CommissionRepository $commissionRepository): Response
    {
        return $this->render('commission/index.html.twig', [
            'commissions' => $commissionRepository->findAll(),
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
}
