<?php

namespace App\EventListener;

use App\Event\SchedulePostEvent;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;

class SchedulePostListener
{
    private $postRepository;
    private $entityManager;

    public function __construct(PostRepository $postRepository, EntityManagerInterface $entityManager)
    {
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
    }

    public function onSchedulePost(SchedulePostEvent $event): void
    {
        $now = new \DateTime();
        $nowFormatted = $now->format('Y-m-d H:i:s');
        $posts = $this->postRepository->createQueryBuilder('p')
            ->andWhere('p.schedule_date <= :now')
            ->andWhere('p.publish_status IS NULL OR p.publish_status = false')
            ->setParameter('now', $nowFormatted)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
        foreach ($posts as $post) {
            $now = new \DateTime();
            $nowFormatted = $now->format('Y-m-d H:i:s');
            $post->setPublishStatus(true);
            $post->setPublishAt($nowFormatted);
            $this->entityManager->persist($post);
        }

        $this->entityManager->flush();
    }
}