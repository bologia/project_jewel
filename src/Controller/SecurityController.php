<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Panier;
use App\Form\InscriptionType;
use App\Repository\PanierRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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

    /**
     * @Route("/listecommand/{page}", name="listecommand", requirements={"page": "\d+"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function listecommand(PanierRepository $repo, $page = 1) {

        $limite = 10;

        $start = $page * $limite - $limite;

        $panierval = $repo->findAllValid($limite,$start); //ceci est dans le panierrepository

        $total = count($repo->findAll());

        $pages = ceil($total / $limite);

        return $this->render('bijouterie/listecommand.html.twig', [
            'panierval' => $panierval,
            'pages' => $pages,
            'page' => $page
        ]);
    }

    /**
     * @Route("/listecommand/finishedpan/{id}", name="finishedpan")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function finishedPan(Panier $panier, ObjectManager $manager) {
        
        $panier->setFinishedPanier(true);
        
        $manager->persist($panier);
        $manager->flush();

        return $this->redirectToRoute('listecommand');
    }
}
