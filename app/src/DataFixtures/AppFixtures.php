<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private $passwordHasher;
    private $userRepository;

    public function __construct(UserPasswordHasherInterface $passwordHasher , UserRepository $userRepository)
    {
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager , ): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->safeEmail);
            $user->setFullName($faker->name);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword('$2y$13$/kJHfJfm5pbZg1J4jPjImeej2pq7OqvDAoy3/amwW0sF.7OjMInSW');  // password
            $manager->persist($user);
            $this->addReference("user_$i", $user);
        }
        for ($i = 0; $i < 100; $i++) {
            $userReference = $this->getReference("user_" . $faker->numberBetween(0, 9));
            $post = new Post();
            $post->setTitle($faker->name);
            $post->setDescription($faker->name);
            $post->setScheduleDate($faker->dateTime->format("Y-m-d H:i:s"));
            $post->setUser($userReference);
            $manager->persist($post);
        }
        $manager->flush();

    }
}
