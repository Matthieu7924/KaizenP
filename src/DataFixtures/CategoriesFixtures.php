<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriesFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger) {
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $parent = new Categorie();
        $parent->setNom('Drones');
        $parent->setSlug($this->slugger->slug($parent->getNom()));
        $manager->persist($parent);
        $this->addReference('parent-category', $parent);


        $categorie = new Categorie();
        $categorie->setNom('Espions industrie');
        $categorie->setSlug($this->slugger->slug($categorie->getNom()));
        $categorie->setParent($parent);
        $manager->persist($categorie);
        $this->addReference('cat-1', $categorie);


        $categorie = new Categorie();
        $categorie->setNom('Espions commerce');
        $categorie->setSlug($this->slugger->slug($categorie->getNom()));
        $categorie->setParent($parent);
        $manager->persist($categorie);
        $this->addReference('cat-2', $categorie);


        $categorie = new Categorie();
        $categorie->setNom('Sports Equipement');
        $categorie->setSlug($this->slugger->slug($categorie->getNom()));
        $manager->persist($categorie);
        $this->addReference('cat-3', $categorie);



        $manager->flush();
    }
}
