<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/sortie/new', name: 'sortie_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sortie->setOrganisateur($this->getUser());
            $sortie->setSiteOrganisateur($this->getUser()->getSite());

            $em->persist($sortie);
            $em->flush();

            $this->addFlash('success', 'Sortie créée avec succès !');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('sortie/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/sortie/{id}', name: 'sortie_show', requirements: ['id' => '\d+'])]
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }
    #[Route('/accueil', name: 'app_home')]
    public function home(EntityManagerInterface $em): Response
    {
        $sorties = $em->getRepository(Sortie::class)->findAll();

        return $this->render('sortie/index.html.twig', [
            'sorties' => $sorties,
        ]);
    }
    #[Route('/sortie/{id}/subscribe', name: 'sortie_subscribe', methods: ['POST'])]
    public function subscribe(Sortie $sortie, EntityManagerInterface $em, Request $request): Response
    {
        $token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('subscribe' . $sortie->getId(), $token)) {
            $sortie->addParticipant($this->getUser());
            $em->flush();
            $this->addFlash('success', 'Inscription réussie !');
        }

        return $this->redirectToRoute('app_home');
    }

    #[Route('/sortie/{id}/unsubscribe', name: 'sortie_unsubscribe', methods: ['POST'])]
    public function unsubscribe(Sortie $sortie, EntityManagerInterface $em, Request $request): Response
    {
        $token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('unsubscribe' . $sortie->getId(), $token)) {
            $sortie->removeParticipant($this->getUser());
            $em->flush();
            $this->addFlash('warning', 'Vous êtes désinscrit de cette sortie.');
        }

        return $this->redirectToRoute('app_home');
    }

}
