<?php

namespace App\Controller;

use App\Entity\Youtube;
use App\Form\YoutubeType;
use App\Repository\YoutubeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class YoutubeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $em, YoutubeRepository $youtubeRepository): Response
    {
        $youtube = new Youtube(); // on créé un objet Youtube vide

        $form = $this->createForm(YoutubeType::class, $youtube); // on utilise le form YoutubeType, insérer l'objet $youtube

        $form->handleRequest($request); //récuperer ce qui provient du form

        if ($form->isSubmitted() && $form->isValid()) {
            $youtube = $form->getData();

            $em->persist($youtube);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }


        return $this->render('youtube/index.html.twig', [
            /* 'controller_name' => 'YoutubeController' */
            'form' => $form->createView(),
            'youtubes' => $youtubeRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_video')]
    public function video(Youtube $youtube): Response
    {
        return $this->render('youtube/video.html.twig', [
            'name' => $youtube->getName(),
            'url' => $youtube->getUrl(),
        ]);
    }
};
