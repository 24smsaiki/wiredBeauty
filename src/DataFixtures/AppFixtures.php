<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setIsVerified(true);
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                "azerty"
            )
        );
        $user->setEmail('user@user.fr');
        $manager->persist($user);

        $admin = new User();
        $admin->setIsVerified(true);
        $admin->setRoles(["ROLE_ADMIN"]);
        $admin->setUser('WB_1_00001');
        $admin->setPassword(
            $this->passwordHasher->hashPassword(
                $admin,
                "azerty"
            )
        );
        $admin->setEmail('admin@admin.fr');
        $manager->persist($admin);

        $manager->flush();
    }
}