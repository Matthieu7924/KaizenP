<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(CategorieRepository $categorieRepo): Response
    {
        // $categories = $categorieRepo->findBy([],['categorieOrder'=>'asc']);
        return $this->render('main/index.html.twig',[
            'categories' => $categorieRepo->findBy([],
            ['categorieOrder'=>'asc'])
        ]);
    }
}
