<?php

namespace App\Repository;

use App\Entity\Laudos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Laudos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Laudos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Laudos[]    findAll()
 * @method Laudos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LaudosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Laudos::class);
    }

    public function buscaExames()
    {
        return $this
            ->createQueryBuilder('e')
            ->select('e')
            ->getQuery()
            ->getResult();
    }
}