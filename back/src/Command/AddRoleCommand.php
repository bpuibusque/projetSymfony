<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:add-role',
    description: 'Add a role to a user.',
)]
class AddRoleCommand extends Command
{
    private $userRepository;
    private $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the user.')
            ->addArgument('role', InputArgument::REQUIRED, 'The role to add.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $role = strtoupper($input->getArgument('role'));

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            $output->writeln('<error>User not found!</error>');
            return Command::FAILURE;
        }

        $roles = $user->getRoles();
        if (!in_array($role, $roles)) {
            $roles[] = $role;
            $user->setRoles($roles);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $output->writeln('<info>Role added successfully!</info>');
        } else {
            $output->writeln('<comment>User already has this role.</comment>');
        }

        return Command::SUCCESS;
    }
}
