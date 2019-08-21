<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) {
        $user = new User();

        $form = $this->createForm(InscriptionType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getMdpUser());
            $user->setMdpUser($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('connexion');
        }

        return $this->render('bijouterie/inscription.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/connexion", name="connexion")
     */
    public function connexion(AuthenticationUtils $utils) {
        return $this->render('bijouterie/connexion.html.twig', [
            'error' => $utils->getLastAuthenticationError() !== null
        ]);
    }

    /**
     * @Route("/deconnexion", name="deconnexion")
     */
    public function deconnexion() {}
}
