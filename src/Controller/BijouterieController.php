<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BijouterieController extends AbstractController
{
    /**
     * @Route("/bijouterie", name="bijouterie")
     */
    public function index()
    {
        return $this->render('bijouterie/index.html.twig', [
            'controller_name' => 'BijouterieController',
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home() {
        return $this->render('bijouterie/home.html.twig');
    }
}
