<?php

namespace App\Repository;

use App\Entity\CommandeMateriel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommandeMateriel>
 *
 * @method CommandeMateriel|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandeMateriel|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandeMateriel[]    findAll()
 * @method CommandeMateriel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeMaterielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandeMateriel::class);
    }
}
