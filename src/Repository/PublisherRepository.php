<?php

namespace App\Repository;

use App\Entity\Publisher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Publisher>
 */
class PublisherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publisher::class);
    }

    public function delete(Publisher $publisher): void
    {
        $this->getEntityManager()->remove($publisher);
    }

    public function persist(Publisher $publisher): void
    {
        $this->getEntityManager()->persist($publisher);
    }

    public function save(): void
    {
        $this->getEntityManager()->flush();
    }
}