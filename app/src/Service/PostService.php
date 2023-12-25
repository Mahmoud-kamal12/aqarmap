<?php

namespace App\Service;

use App\Entity\Post;
use App\Repository\PostRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PostService
{
    private $postRepository;
    private $tokenStorage;
    private $dispatcher;

    public function __construct(PostRepository $postRepository , TokenStorageInterface $tokenStorage , EventDispatcherInterface $dispatcher)
    {
        $this->postRepository = $postRepository;
        $this->tokenStorage = $tokenStorage;
        $this->dispatcher = $dispatcher;

    }

    public function createPost(string $title, string $description , string $schedule_date = null)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        $post = new Post();
        $post->setTitle($title);
        $post->setDescription($description);
        $post->setUser($user);
        if (empty($schedule_date)){
            $post->setPublishStatus(true);
            $publish_at = new \DateTime();
            $publish_at = $publish_at->format('Y-m-d H:i:s');
            $post->setPublishAt($publish_at);
        }
        else{
            $post->setScheduleDate($schedule_date);
        }
        $post = $this->postRepository->createPost($post);
        return $post->toArray();
    }

    public function findAll(){
        $data = [];
        $posts = $this->postRepository->findAll();
        foreach ($posts as $post) {
            $data[] = $post->toArray();
        }
        return $data;
    }

    /**
     * @throws \Exception
     */
    public function find($id): array
    {

        if (!empty($post = $this->postRepository->find($id))){
            return $post->toArray();
        }
        throw new \Exception("post id not found");
    }


    /**
     * @throws \Exception
     */
    public function updatePost($id, string $title, string $description, ?string $schedule_date): array
    {
        if (!empty($post = $this->postRepository->find($id))){
            $post->setTitle($title);
            $post->setDescription($description);
            if (empty($schedule_date)){
                $post->setPublishStatus(true);
                $publish_at = new \DateTime();
                $publish_at = $publish_at->format('Y-m-d H:i:s');
                $post->setPublishAt($publish_at);
            }else{
                $post->setScheduleDate($schedule_date);
            }
            return $this->postRepository->updatePost($post)->toArray();
        }
        throw new \Exception("post id not found");

    }

    /**
     * @throws \Exception
     */
    public function deletePost($id): array
    {
        if (!empty($post = $this->postRepository->find($id))){
            $user = $this->tokenStorage->getToken()->getUser();
            if ($post->getUser() !== $user) {
                throw new \Exception("You are not allowed to delete this post");
            }
            return $this->postRepository->deletePost($post);
        }
        throw new \Exception("post id not found");

    }


}