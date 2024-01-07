<?php

namespace App\Controller;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/produits', name: 'produit_')]
class ProduitController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private EntityManagerInterface $doctrine;

    public function __construct(EntityManagerInterface $entityManager, EntityManagerInterface $doctrine)
    {
        $this->entityManager = $entityManager;
        $this->doctrine = $doctrine;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }

    #[Route('/nouveau', name: 'add')]
    public function add(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $produit = new Produit();
        $form = $this->createForm(\App\Form\ProduitType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $form->getData();
            $this->entityManager->persist($produit);
            $this->entityManager->flush();
            $this->addFlash(
                'success',
                'Produit correctement ajouté!'
            );
            return $this->redirectToRoute('produit_index');
        }
        return $this->render('produit/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{slug}', name: 'details')]
    public function details(string $slug): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $produit = $this->entityManager->getRepository(Produit::class)->findOneBy(['slug' => $slug]);
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }
        return $this->render('produit/details.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/produits/{slug}/delete', name: 'produit_delete')]
    public function delete(string $slug): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $produit = $this->entityManager->getRepository(Produit::class)->findOneBy(['slug' => $slug]);
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }
        $this->entityManager->remove($produit);
        $this->entityManager->flush();
        $this->addFlash(
            'success',
            'Produit correctement supprimé!'
        );
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }
}
