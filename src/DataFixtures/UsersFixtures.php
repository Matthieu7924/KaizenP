<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class UsersFixtures extends Fixture
{
public function __construct(
    private UserPasswordHasherInterface $passwordEncoder,
    private SluggerInterface $slugger) {
}


    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@gmail.com');
        $admin->setNom('Garban');
        $admin->setPrenom('Matthieu');
        $admin->setAdresse('22 rue du code');
        $admin->setCp('33185');
        $admin->setVille('LeHaillan');
        $admin->setPassword(
            $this->passwordEncoder->hashPassword($admin,'admin')
        );
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);


        $faker = Faker\Factory::create('fr_FR');
        // le faker permet d'avoir des utilisateurs au regitre français

        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setNom($faker->lastName());
            $user->setPrenom($faker->firstName());
            $user->setAdresse($faker->streetAddress());
            //on est obligé de supprimer l'espace sinon cp trop long
            $user->setCp(str_replace(' ','',$faker->postCode));
            $user->setVille($faker->city);
            $user->setPassword(
                $this->passwordEncoder->hashPassword($user, 'secret')
            );
            $manager->persist($user);
        }


        $manager->flush();
    }
}
