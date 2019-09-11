<?php

namespace App\Controller;

use App\Entity\News;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NewsController extends AbstractController
{
    /**
     * @Route("/actu", name="actu")
     */
    public function actu() {
        $repo = $this->getDoctrine()->getRepository(News::class);

        $newss = $repo->findAll();
        /*
        $repo->find(1) : news id 1
        $repo->findBy() : critere
        $repo->findOneBy() : idem mais un seul
        */
        return $this->render('bijouterie/actu.html.twig', [
            'newss' => $newss
        ]);
    }

    /**
     * @Route("/ajoutnews", name="ajoutnews")
     * @Security("is_granted('ROLE_ADMIN')")
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
            $news->setCreatedAt(new \DateTime('now', new \DateTimeZone("Europe/Paris")));

            $imagee = $formnews->get('imagNews')->getData();
                if($imagee != NULL) {
                    $namee = $news->getId().''.mt_rand(0,1000000);
                    $imageNamee = $namee.'.'. $imagee->guessExtension();
                    $imagee->move(
                        $this->getParameter('image_directory'),
                        $imageNamee
                    );
                    $news->setImagNews($imageNamee);
                } else {
                    $news->setImagNews('imag/marseille.jpg');
                }

            $manager->persist($news);
            $manager->flush();

            return $this->redirectToRoute('actu');
        }

        return $this->render('bijouterie/ajoutnews.html.twig', [
            'formnews' => $formnews->createView()
        ]);
    }

    /**
     * @Route("/{id}/modifnews", name="modifnews")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function modifnews(News $news, Request $request, ObjectManager $manager) {

        $formnews = $this->createFormBuilder($news)
                         ->add('titreNews')
                         ->add('textNews')
                         ->getForm();

        $formnews->handleRequest($request);

        if($formnews->isSubmitted() && $formnews->isValid()) {

            $manager->persist($news);
            $manager->flush();

            return $this->redirectToRoute('actu');
        }

        return $this->render('bijouterie/modifnews.html.twig', [
            'formnews' => $formnews->createView()
        ]);
    }

    /**
     * @Route("/{id}/deletenews", name="deletenews")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function deletenews(News $news, Request $request)
    {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($news);
            $manager->flush();

        return $this->redirectToRoute('actu');
    }
}
