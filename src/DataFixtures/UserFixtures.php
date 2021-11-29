<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private UserPasswordHasherInterface $userPasswordHasher;

    /**
     * @param UserPasswordHasherInterface $userPasswordHasher
     */
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail(sprintf("email+%d@email.com", $i));
            $user->setPseudo(sprintf("pseudo+%d", $i));
            $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
            $manager->persist($user);
            $this->setReference(sprintf("user-%d", $i), $user);
        }
        $manager->flush();
    }
}