<?php

namespace App\Controller;

use App\Entity\Receitas;
use App\Form\ReceitasType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/receitas")
 */
class ReceitasController extends AbstractController
{
    /**
     * @Route("/", name="receitas_index", methods={"GET"})
     */
    public function index(): Response
    {
        $receitas = $this->getDoctrine()
            ->getRepository(Receitas::class)
            ->findAll();

        return $this->render('receitas/index.html.twig', [
            'receitas' => $receitas,
        ]);
    }

    /**
     * @Route("/new", name="receitas_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $receita = new Receitas();
        $form = $this->createForm(ReceitasType::class, $receita);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($receita);
            $entityManager->flush();
            $this->addFlash('success', 'O item foi criado com sucesso.');
            return $this->redirectToRoute('receitas_index');
        }

        return $this->render('receitas/new.html.twig', [
            'receita' => $receita,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="receitas_show", methods={"GET"})
     */
    public function show(Receitas $receita): Response
    {
        return $this->render('receitas/show.html.twig', [
            'receita' => $receita,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="receitas_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Receitas $receita): Response
    {
        $form = $this->createForm(ReceitasType::class, $receita);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'O item foi editado com sucesso.');
            return $this->redirectToRoute('receitas_index');
        }

        return $this->render('receitas/edit.html.twig', [
            'receita' => $receita,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="receitas_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Receitas $receita): Response
    {
        if ($this->isCsrfTokenValid('delete'.$receita->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($receita);
            $entityManager->flush();
            $this->addFlash('success', 'O item foi excluido com sucesso.');
        }

        return $this->redirectToRoute('receitas_index');
    }
}
