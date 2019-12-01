<?php

namespace App\Controller;

use App\Entity\Pacientes;
use App\Entity\Prontuarios;
use App\Form\ProntuariosType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/prontuarios")
 */
class ProntuariosController extends AbstractController
{
    /**
     * @Route("/", name="prontuarios_index", methods={"GET"})
     */
    public function index(): Response
    {
        $prontuarios = $this->getDoctrine()
            ->getRepository(Prontuarios::class)
            ->findAll();

        return $this->render('prontuarios/index.html.twig', [
            'prontuarios' => $prontuarios,
        ]);
    }

    /**
     * @Route("/new", name="prontuarios_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $prontuario = new Prontuarios();
        if($request->query->get('paciente')){
            $paciente = $this->getDoctrine()->getRepository(Pacientes::class)->find($request->query->get('paciente'));
            $prontuario->setIdpacientes($paciente);
        }
        $form = $this->createForm(ProntuariosType::class, $prontuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($prontuario);
            $entityManager->flush();
            $this->addFlash('success', 'O item foi criado com sucesso.');
            return $this->redirectToRoute('prontuarios_index');
        }

        return $this->render('prontuarios/new.html.twig', [
            'prontuario' => $prontuario,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="prontuarios_show", methods={"GET"})
     */
    public function show(Prontuarios $prontuario): Response
    {
        return $this->render('prontuarios/show.html.twig', [
            'prontuario' => $prontuario,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="prontuarios_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Prontuarios $prontuario): Response
    {
        $form = $this->createForm(ProntuariosType::class, $prontuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'O item foi editado com sucesso.');
            return $this->redirectToRoute('prontuarios_index');
        }

        return $this->render('prontuarios/edit.html.twig', [
            'prontuario' => $prontuario,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="prontuarios_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Prontuarios $prontuario): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prontuario->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($prontuario);
            $entityManager->flush();
            $this->addFlash('success', 'O item foi excluido com sucesso.');
        }

        return $this->redirectToRoute('prontuarios_index');
    }
}
