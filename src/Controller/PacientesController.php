<?php

namespace App\Controller;

use App\Entity\Acesso;
use App\Entity\Pacientes;
use App\Form\AcessoType;
use App\Form\PacientesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pacientes")
 */
class PacientesController extends AbstractController
{
    /**
     * @Route("/", name="pacientes_index", methods={"GET"})
     */
    public function index(): Response
    {
        $pacientes = $this->getDoctrine()
            ->getRepository(Pacientes::class)
            ->findAll();

        return $this->render('pacientes/index.html.twig', [
            'pacientes' => $pacientes,
        ]);
    }

    /**
     * @Route("/new", name="pacientes_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $paciente = new Pacientes();
        $form = $this->createForm(PacientesType::class, $paciente);
        $form->handleRequest($request);
        $acesso = new Acesso();
        $form2 = $this->createForm(AcessoType::class, $acesso);
        $form2->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form2->isSubmitted() && $form2->isValid()) {
//                $entityManager = $this->getDoctrine()->getManager();
                $entityManager = $this->getDoctrine()->getManagerForClass(Acesso::class);
                $paciente->setDataCadastro(new \DateTime());
                $acesso->setPacientes($paciente);
                $acesso->setPassword(sha1(md5($acesso->getPassword())));
                $entityManager->persist($paciente);
                $entityManager->persist($acesso);
                $entityManager->flush();
            }
            $this->addFlash('success', 'O item foi criado com sucesso.');
            return $this->redirectToRoute('pacientes_index');
        }

        return $this->render('pacientes/new.html.twig', [
            'paciente' => $paciente,
            'form' => $form->createView(),
            'form2' => $form2->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="pacientes_show", methods={"GET"})
     */
    public function show(Pacientes $paciente): Response
    {
        return $this->render('pacientes/show.html.twig', [
            'paciente' => $paciente,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="pacientes_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Pacientes $paciente): Response
    {
        $form = $this->createForm(PacientesType::class, $paciente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'O item foi editado com sucesso.');
            return $this->redirectToRoute('pacientes_index');
        }

        return $this->render('pacientes/edit.html.twig', [
            'paciente' => $paciente,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="pacientes_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Pacientes $paciente): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paciente->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($paciente);
            $entityManager->flush();
            $this->addFlash('success', 'O item foi excluido com sucesso.');
        }

        return $this->redirectToRoute('pacientes_index');
    }
}
