<?php

namespace App\Command;

use App\Service\ServerSideIdentifier;
use phpbrowscap\Browscap;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class InitBrowscapCommand extends Command
{
    protected static $defaultName = 'app:init-browscap';
    private ServerSideIdentifier $serverSideIdentifier;

    public function __construct(
        ServerSideIdentifier $serverSideIdentifier
    )
    {
        parent::__construct(null);

        $this->serverSideIdentifier = $serverSideIdentifier;
    }

    protected function configure()
    {
        $this
            ->setDescription('Init browsecap')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->serverSideIdentifier->init();

        $io->success('Sucessfully initialized browscap.');

        return 0;
    }
}
