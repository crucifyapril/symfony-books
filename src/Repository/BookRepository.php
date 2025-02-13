<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function findAllBooksWithAuthorPublisher(): array
    {
        return $this->createQueryBuilder('b')
            ->select('b.id', 'b.title', 'b.year', 'p.title AS publisherTitle', 'a.lastName')
            ->leftJoin('b.authors', 'a')
            ->leftJoin('b.publisher', 'p')
            ->getQuery()
            ->getResult();
    }

    public function persist(Book $book): void
    {
        $this->getEntityManager()->persist($book);
    }

    public function delete(Book $book): void
    {
        $this->getEntityManager()->remove($book);
    }

    public function save(): void
    {
        $this->getEntityManager()->flush();
    }
}
