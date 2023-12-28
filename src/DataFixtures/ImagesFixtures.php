<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class ImagesFixtures extends Fixture implements DependentFixtureInterface
//le DependentFixturesInterface va permettre de modifier l'ordre de chargement des datfixtures
//et de charger les prodiots avant les images
//et ainsi d'éviter qu"une image ne soit rattachée à un produit non existant
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i=1;$i<=100;$i++)
        {
            $img=new Image();
            $randomName = $faker->word . '_' . $faker->randomNumber() . '.' . $faker->randomElement(['jpg', 'jpeg', 'png']);
            $img->setNom($randomName);

            $produit = $this->getReference('prod-' . rand(1, 10));
            $img->setProduit($produit);
            $manager->persist($img);
        }
        $manager->flush();
    }

    public function getDependencies():array
    {
        return[
            ProduitsFixtures::class
        ];
    }
}
