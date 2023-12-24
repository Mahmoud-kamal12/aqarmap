<?php

namespace App\Command;

use App\Event\SchedulePostEvent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

#[AsCommand(
    name: 'app:schedule-posts',
    description: 'schedule posts',
    hidden: false,
    aliases: ['app:schedule-posts']
)]
class SchedulePostCommand extends Command
{
    private EventDispatcherInterface $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        parent::__construct();

        $this->dispatcher = $dispatcher;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:schedule-posts')
            ->setDescription('Dispatch SchedulePostEvent every second');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new SymfonyStyle($input, $output);

        while (true) {
            $stopwatch = new Stopwatch();
            $stopwatch->start('schedule-posts');

            $event = new SchedulePostEvent();
            $this->dispatcher->dispatch($event, SchedulePostEvent::NAME);

            sleep(1);

            $event = $stopwatch->stop('schedule-posts');
            $io->success(sprintf('Dispatched SchedulePostEvent. Elapsed time: %s seconds', $event->getDuration() / 1000));
        }
    }
}