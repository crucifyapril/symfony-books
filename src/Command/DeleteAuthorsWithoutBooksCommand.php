<?php

namespace App\Command;

use App\Repository\AuthorRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'delete-authors-without-books',
    description: 'Удаляет всех авторов, которые не имеют книг'
)]
class DeleteAuthorsWithoutBooksCommand extends Command
{
    public function __construct(private readonly AuthorRepository $authorRepository)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $authorsWithoutBooks = $this->authorRepository->findAuthorsWithoutBooks();

        if (empty($authorsWithoutBooks)) {
            $output->writeln('Нет авторов без книг.');
            return Command::SUCCESS;
        }

        foreach ($authorsWithoutBooks as $author) {
            $this->authorRepository->delete($author);
        }

        $this->authorRepository->save();

        $output->writeln('Все авторы без книг были удалены.');

        return Command::SUCCESS;
    }
}
