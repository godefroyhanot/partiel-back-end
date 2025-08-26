<?php

namespace App\Repository;

use App\Entity\Materiel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Materiel>
 *
 * @method Materiel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Materiel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Materiel[]    findAll()
 * @method Materiel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Materiel::class);
    }

    /**
     * Trouve tous les matériels disponibles (quantité > 0)
     */
    public function findAvailable(): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.quantiteDisponible > 0')
            ->orderBy('m.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les matériels par nom (recherche partielle)
     */
    public function findByNomLike(string $nom): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.nom LIKE :nom')
            ->setParameter('nom', '%' . $nom . '%')
            ->orderBy('m.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
