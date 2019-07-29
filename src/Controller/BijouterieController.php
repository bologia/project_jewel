<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
        return $this->render('bijouterie/actu.html.twig');
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
}
