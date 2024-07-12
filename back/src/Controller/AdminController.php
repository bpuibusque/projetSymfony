<?php

namespace App\Controller;

use App\Entity\Privilege;
use App\Form\AdminPrivilegeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/privileges', name: 'admin_privileges', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function managePrivileges(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdminPrivilegeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $action = $data['action'];
            $user = $data['user'];
            $commission = $data['commission'];
            $role = $data['role'];

            if ($action === 'add') {
                $privilege = new Privilege();
                $privilege->setUser($user);
                $privilege->setCommission($commission);
                $privilege->setRole($role);

                $entityManager->persist($privilege);
                $entityManager->flush();
            } elseif ($action === 'remove') {
                $privilege = $entityManager->getRepository(Privilege::class)->findOneBy([
                    'user' => $user,
                    'commission' => $commission,
                    'role' => $role,
                ]);

                if ($privilege) {
                    $entityManager->remove($privilege);
                    $entityManager->flush();
                }
            }

            return $this->redirectToRoute('admin_privileges');
        }

        $privileges = $entityManager->getRepository(Privilege::class)->findAll();
        $groupedPrivileges = [];

        foreach ($privileges as $privilege) {
            $userEmail = $privilege->getUser()->getEmail();
            if (!isset($groupedPrivileges[$userEmail])) {
                $groupedPrivileges[$userEmail] = [];
            }
            $groupedPrivileges[$userEmail][] = $privilege;
        }

        return $this->render('admin/manage_privileges.html.twig', [
            'form' => $form->createView(),
            'groupedPrivileges' => $groupedPrivileges,
        ]);
    }
}
