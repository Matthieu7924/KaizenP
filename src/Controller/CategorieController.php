<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/categories', name: 'categorie_')]
class CategorieController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ProduitRepository $produitRepository;

    public function __construct(EntityManagerInterface $entityManager, ProduitRepository $produitRepository)
    {
        $this->entityManager = $entityManager;
        $this->produitRepository = $produitRepository;
    }

    #[Route('/{slug}', name: 'listeProd')]
    public function details(string $slug): Response
    {
        $categorie = $this->entityManager->getRepository(Categorie::class)->findOneBy(['slug' => $slug]);

        if (!$categorie) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }

        // $produits = $this->produitRepository->findBy(['categorie' => $categorie]);
        $produits = $categorie->getProduits();

        return $this->render('categorie/listeProd.html.twig', [
            'categorie' => $categorie,
            'produits' => $produits,
        ]);
    }
}