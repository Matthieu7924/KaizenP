<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;


#[Route('/produits', name: 'produit_')]
class ProduitController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UploaderHelper $uploaderHelper;
    


    public function __construct(EntityManagerInterface $entityManager, UploaderHelper $uploaderHelper)
    {
        $this->entityManager = $entityManager;
        $this->uploaderHelper = $uploaderHelper;

    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }

    #[Route('/nouveau', name: 'add')]
    #[Route('/nouveau', name: 'add')]
    public function add(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persistez l'entité, VichUploader s'occupera du téléchargement automatique du fichier
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

        return $this->redirectToRoute('produit_index');
    }
}
