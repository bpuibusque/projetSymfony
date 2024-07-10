<?php

namespace App\Repository;

use App\Entity\Privilege;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Privilege>
 *
 * @method Privilege|null find($id, $lockMode = null, $lockVersion = null)
 * @method Privilege|null findOneBy(array $criteria, array $orderBy = null)
 * @method Privilege[]    findAll()
 * @method Privilege[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrivilegeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Privilege::class);
    }

    // Add your custom methods here
}
