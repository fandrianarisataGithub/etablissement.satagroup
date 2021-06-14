<?php

namespace App\Controller;

use App\Entity\Universite;
use App\Form\UniversiteType;
use App\Repository\UniversiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/universite")
 */
class UniversiteController extends AbstractController
{
    /**
     * @Route("/", name="universite_index", methods={"GET"})
     */
    public function index(UniversiteRepository $universiteRepository): Response
    {
        return $this->render('universite/index.html.twig', [
            'universites' => $universiteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="universite_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $universite = new Universite();
        $form = $this->createForm(UniversiteType::class, $universite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($universite);
            $entityManager->flush();

            return $this->redirectToRoute('universite_index');
        }

        return $this->render('universite/new.html.twig', [
            'universite' => $universite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="universite_show", methods={"GET"})
     */
    public function show(Universite $universite): Response
    {
        return $this->render('universite/show.html.twig', [
            'universite' => $universite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="universite_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Universite $universite): Response
    {
        $form = $this->createForm(UniversiteType::class, $universite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('universite_index');
        }

        return $this->render('universite/edit.html.twig', [
            'universite' => $universite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="universite_delete", methods={"POST"})
     */
    public function delete(Request $request, Universite $universite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$universite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($universite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('universite_index');
    }
}
