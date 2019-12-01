<?php

namespace App\Controller;

use App\Entity\TipoConsulta;
use App\Form\TipoConsultaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tipo/consulta")
 */
class TipoConsultaController extends AbstractController
{
    /**
     * @Route("/", name="tipo_consulta_index", methods={"GET"})
     */
    public function index(): Response
    {
        $tipoConsultas = $this->getDoctrine()
            ->getRepository(TipoConsulta::class)
            ->findAll();

        return $this->render('tipo_consulta/index.html.twig', [
            'tipo_consultas' => $tipoConsultas,
        ]);
    }

    /**
     * @Route("/new", name="tipo_consulta_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tipoConsultum = new TipoConsulta();
        $form = $this->createForm(TipoConsultaType::class, $tipoConsultum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tipoConsultum);
            $entityManager->flush();
            $this->addFlash('success', 'O item foi criado com sucesso.');
            return $this->redirectToRoute('tipo_consulta_index');
        }

        return $this->render('tipo_consulta/new.html.twig', [
            'tipo_consultum' => $tipoConsultum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idtipoConsulta}", name="tipo_consulta_show", methods={"GET"})
     */
    public function show(TipoConsulta $tipoConsultum): Response
    {
        return $this->render('tipo_consulta/show.html.twig', [
            'tipo_consultum' => $tipoConsultum,
        ]);
    }

    /**
     * @Route("/{idtipoConsulta}/edit", name="tipo_consulta_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TipoConsulta $tipoConsultum): Response
    {
        $form = $this->createForm(TipoConsultaType::class, $tipoConsultum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'O item foi editado com sucesso.');
            return $this->redirectToRoute('tipo_consulta_index');
        }

        return $this->render('tipo_consulta/edit.html.twig', [
            'tipo_consultum' => $tipoConsultum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idtipoConsulta}", name="tipo_consulta_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TipoConsulta $tipoConsultum): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tipoConsultum->getIdtipoConsulta(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tipoConsultum);
            $entityManager->flush();
            $this->addFlash('success', 'O item foi excluido com sucesso.');
        }

        return $this->redirectToRoute('tipo_consulta_index');
    }
}
