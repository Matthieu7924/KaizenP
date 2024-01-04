<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\SendMailService;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    
    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        UserAuthenticatorInterface $userAuthenticator, 
        UserAuthenticator $authenticator, 
        EntityManagerInterface $entityManager,
        SendMailService $mail
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Générer le token et le définir
            $emailVerificationToken = $this->generateToken(); // Utilisez $this->generateToken() au lieu de generateToken()
            $user->setEmailVerificationToken($emailVerificationToken);

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $mail->send(
                'no-reply@kpes.com',
                $user->getEmail(),
                'Vérification Email',
                'register',
                [
                    'user'=>$user
                ]
            );

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    // Ajoutez cette méthode pour générer le token
    private function generateToken(): string
    {
        // Logique de génération de token (vous pouvez utiliser une bibliothèque existante)
        return bin2hex(random_bytes(32)); // Exemple simple : génération d'une chaîne hexadécimale aléatoire
    }



    #[Route('/verification-email/{token}', name: 'app_verify_email')]
    public function verifyEmail(string $token, EntityManagerInterface $entityManager): Response
    {
        // Recherchez l'utilisateur avec le token d'activation
        $user = $entityManager->getRepository(User::class)->findOneBy(['emailVerificationToken' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('Token d\'activation invalide');
        }

        // Vérifiez si le token n'a pas expiré (ajoutez votre propre logique ici)

        // Activez l'utilisateur
        $user->setIsVerified(true);
        $user->setEmailVerificationToken(null); // ou marquez le token comme utilisé selon votre logique

        $entityManager->flush();

        // Redirigez l'utilisateur vers la page de confirmation ou autre
        return $this->redirectToRoute('app_confirmation');
    }

    #[Route('/confirmation', name: 'app_confirmation')]
    public function confirmation(): Response
    {
        return $this->render('registration/confirmation.html.twig');
    }

}
