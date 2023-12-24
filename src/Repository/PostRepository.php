<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function createPost(Post $post): Post
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($post);
        $entityManager->flush();

        return $post ;
    }

    public function updatePost(Post $post): Post
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($post);
        $entityManager->flush();

        return $post;
    }

    public function deletePost(Post $post): array
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($post);
        $entityManager->flush();
        return [];
    }
}
