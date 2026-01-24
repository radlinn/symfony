<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate_article',
    description: 'Generates new article',
)]
class GenerateArticleCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('title', InputArgument::OPTIONAL, 'Title')
            ->addArgument('content', InputArgument::OPTIONAL, 'Content')
            ->addArgument('author', InputArgument::OPTIONAL, 'Author');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $title = $input->getArgument('title') ?? 'Article title';
        $content = $input->getArgument('content') ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi auctor nisi efficitur ante dapibus porta. Cras a augue ipsum. Sed tincidunt volutpat ante. Proin sit amet elementum lectus. Donec fringilla lorem non erat lacinia mollis. Praesent iaculis nulla a ex ultricies mattis at id nulla. Aliquam erat volutpat.';
        $author = $input->getArgument('author') ?? 'admin';

        $article = new Article();
        $article
            ->setTitle($title)
            ->setContent($content)
            ->setAuthor($author);

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        $output->writeln('Artykuł został zapisany w bazie!');
        $output->writeln('Tytuł: ' . $title);
        $output->writeln('Autor: ' . $author);

        return Command::SUCCESS;
    }
}