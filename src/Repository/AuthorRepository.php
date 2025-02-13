<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function create(): QueryBuilder
    {
        return $this->createQueryBuilder('a');
    }

    public function findAuthorsWithoutBooks(): QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->leftJoin('a.books', 'b')
            ->where('b.id IS NULL')
            ->getQuery()
            ->getResult();
    }

    public function delete(Author $author): void
    {
        $this->getEntityManager()->remove($author);
    }

    public function persist(Author $author): void
    {
        $this->getEntityManager()->persist($author);
    }

    public function save(): void
    {
        $this->getEntityManager()->flush();
    }
}