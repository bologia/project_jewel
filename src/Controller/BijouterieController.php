<?php

namespace App\Controller;

use App\Entity\News;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BijouterieController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home() {
        return $this->render('bijouterie/home.html.twig', [
            'controller_name' => 'BijouterieController',
        ]);
    }

    /**
     * @Route("/actu", name="actu")
     */
    public function actu() {
        $repo = $this->getDoctrine()->getRepository(News::class);

        $newss = $repo->findAll();

        return $this->render('bijouterie/actu.html.twig', [
            'newss' => $newss
        ]);
    }

    /**
     * @Route("/marques", name="marques")
     */
    public function marques() {
        return $this->render('bijouterie/marques.html.twig');
    }

    /**
     * @Route("/apropos", name="apropos")
     */
    public function apropos() {
        return $this->render('bijouterie/apropos.html.twig');
    }

    /**
     * @Route("/ajoutnews", name="ajoutnews")
     */
    public function ajoutnews(Request $request, ObjectManager $manager) {
        $news = new News();

        $formnews = $this->createFormBuilder($news)
                         ->add('titreNews')
                         ->add('textNews')
                         ->add('imagNews', FileType::class)
                         ->getForm();

        $formnews->handleRequest($request);

        if($formnews->isSubmitted() && $formnews->isValid()) {
            $news->setCreatedAt(new \DateTime());
            $manager->persist($news);
            $manager->flush();

            //return $this->redirectToRoute('');
        }

        return $this->render('bijouterie/ajoutnews.html.twig', [
            'formnews' => $formnews->createView()
        ]);
    }
}
