<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/produits', name: 'admin_produits')]
class ProdAdminController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private EntityManagerInterface $doctrine;

    public function __construct(EntityManagerInterface $entityManager, EntityManagerInterface $doctrine)
    {
        $this->entityManager = $entityManager;
        $this->doctrine = $doctrine;
    }
    #[Route('/', name:'index')]
    public function index(): Response
    {
        return $this->render('admin/prod/index.html.twig');
    }
    #[Route('/nouveau', name:'add')]
    public function add(): Response
    {
        return $this->render('admin/prod/index.html.twig');
    }


    #[Route('/edition/{id}', name:'edit')]
    public function edit(Produit $produit): Response
    {
        return $this->render('admin/prod/index.html.twig');
    }
    


    #[Route('/suppression/{id}', name:'delete')]
    public function delete(Produit $produit): Response
    {
        return $this->render('admin/prod/index.html.twig');
    }
}