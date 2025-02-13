<?php

namespace App\Command;

use App\Entity\Publisher;
use App\Entity\Book;
use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'seed-database',
    description: 'Заполнение базы данных тестовыми данными',
)]
class SeedDatabaseCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $publisher = new Publisher();
        $publisher->setTitle('Издательство А');
        $publisher->setAddress('Улица Примерная, дом 1');
        $this->em->persist($publisher);

        $author1 = new Author();
        $author1->setFirstName('Иван');
        $author1->setLastName('Иванов');
        $this->em->persist($author1);

        $author2 = new Author();
        $author2->setFirstName('Петр');
        $author2->setLastName('Петров');
        $this->em->persist($author2);

        $author3 = new Author();
        $author3->setFirstName('Сидор');
        $author3->setLastName('Сидоров');
        $this->em->persist($author3);

        $book = new Book();
        $book->setTitle('Книга 1');
        $book->setYear(2020);
        $book->setPublisher($publisher);
        $book->addAuthor($author1);
        $book->addAuthor($author2);
        $this->em->persist($book);

        $book = new Book();
        $book->setTitle('Книга 2');
        $book->setYear(2021);
        $book->setPublisher($publisher);
        $book->addAuthor($author2);
        $this->em->persist($book);

        $book = new Book();
        $book->setTitle('Книга 3');
        $book->setYear(2024);
        $book->setPublisher($publisher);
        $this->em->persist($book);

        $this->em->flush();

        $output->writeln('База данных успешно наполнена тестовыми данными.');

        return Command::SUCCESS;
    }
}
