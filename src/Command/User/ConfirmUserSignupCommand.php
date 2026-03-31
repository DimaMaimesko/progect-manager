<?php

namespace App\Command\User;

use App\Model\User\UseCase\SignUp\Confirm;
use App\ReadModel\User\UserFetcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(
    name: 'app:confirm-user-signup',
    description: 'Manual confirm user signup by id',
)]
class ConfirmUserSignupCommand extends Command
{
    private $users;
    private $handler;

    public function __construct(UserFetcher $users, Confirm\ByHand\Handler $handler)
    {
        $this->users = $users;
        $this->handler = $handler;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:confirm-user-signup')
            ->setDescription('Confirms signed up user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $email = $helper->ask($input, $output, new Question('Email: '));

        if (!$user = $this->users->findForAuth($email)) {
            throw new LogicException('User is not found.');
        }

        $command = new Confirm\ByHand\Command($user->id);
        $this->handler->handle($command);

        $output->writeln('<info>Done!</info>');

        return Command::SUCCESS;
    }
}
