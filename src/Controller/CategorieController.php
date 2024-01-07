<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/categories', name: 'categorie_')]
class CategorieController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        // $categories = $this->getDoctrine()->getManager()->getRepository(Categorie::class)->findAll();
        $categories = $this->entityManager->getRepository(Categorie::class)->findAll();


        // Logique pour afficher la liste des catégories
        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/nouveau', name: 'add')]
    public function add(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($categorie);
            $this->entityManager->flush();

            $this->addFlash('success', 'Catégorie ajoutée avec succès!');

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('categorie/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{slug}/modifier', name: 'edit')]
    public function edit(string $slug, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $categorie = $this->entityManager->getRepository(Categorie::class)->findOneBy(['slug' => $slug]);

        if (!$categorie) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }

        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Catégorie modifiée avec succès!');

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('categorie/edit.html.twig', [
            'form' => $form->createView(),
            'categorie' => $categorie,
        ]);
    }

    #[Route('/{slug}/supprimer', name: 'delete')]
    public function delete(string $slug): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $categorie = $this->entityManager->getRepository(Categorie::class)->findOneBy(['slug' => $slug]);

        if (!$categorie) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }

        $this->entityManager->remove($categorie);
        $this->entityManager->flush();

        $this->addFlash('success', 'Catégorie supprimée avec succès!');

        return $this->redirectToRoute('categorie_index');
    }
}
