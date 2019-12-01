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
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder(
            'select e from App\Entity\Exames e inner join App\Entity\Laudos l on e.id != l.id'
        );

        return $qb->getQuery()->getResult();
    }
}