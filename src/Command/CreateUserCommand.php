<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name:'app:create_user', description:'create a new user', hidden: false, aliases: ['app:add-user'])]
class CreateUserCommand extends Command{

    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher){
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;

        parent::__construct();
    }
    
    protected function configure(): void{
        $this
        ->setDescription('Tworzy nowego uzytkownika')
        ->setHelp('Aby utworzyc nowego uzytkownika uzyj tej komendy')
        ->addArgument('email', InputArgument::REQUIRED, 'Email nowego uytkownika')
        ->addArgument('password', InputArgument::OPTIONAL, 'Haslo nowego uzytkownika');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        
        $email = $input->getArgument('email');
        $plainPassword = $input-> getArgument('password') ?? 'example';
        
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email'=> $email]);
        if($existingUser){
            $output->writeln("Uzytkownik z takim mailem juz istnieje");
            return Command::FAILURE;
        }
        $user = new User();
        $user->setEmail($email);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);

        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        
        $output->writeln('Uzytkownik utworzony pomyslnie!');
        
        return Command::SUCCESS;
    }
}