<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$fullname, $username, $email, $password, $roles]) {
            $user = new User();

            $user->setFullName($fullname)
                ->setUsername($username)
                ->setEmail($email)
                ->setPassword($this->passwordEncoder->encodePassword($user, $password))
                ->setRoles($roles);

            $this->setReference($username, $user);

            $manager->persist($user);
        }
        $manager->flush();
    }

    private function getUserData()
    {
        // [$fullname, $username, $email, $password, $roles]
        return[
            ['amir moshfegh', 'admin_1', 'admin_1@gmail.com', '123456', ['ROLE_ADMIN']],
            ['somayeh motamed', 'admin_2', 'admin_2@gmail.com', '123456', ['ROLE_ADMIN']],
            ['nika moshfegh', 'user_1', 'user_1@gmail.com', '123456', ['ROLE_USER']],
            ['niki moshfegh', 'user_2', 'user_1@gmail.com', '123456', ['ROLE_USER']],
        ];
    }
}
