<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Produit;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProduitsFixtures extends Fixture
{
public function __construct(private SluggerInterface $slugger) {
}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for($i=1;$i<20;$i++)
        {
            $produit = new Produit();
            $produit->setNom($faker->text(5));
            $produit->setDescription($faker->text());
            $produit->setSlug($this->slugger->slug($produit->getNom())->lower());
            $produit->setPrix($faker->numberBetween(900,200000));
            $produit->setStock($faker->numberBetween(0,100));
            //on récupère une des categories dédinie dans le catgegorieFixture
            $categorie = $this->getReference('cat-' . rand(1, 3));
            $produit->setCategorie($categorie);

            $manager->persist($produit);
            $this->addReference('prod-'.$i,$produit);
        }

        $manager->flush();
    }
}
