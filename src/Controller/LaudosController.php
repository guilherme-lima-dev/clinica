<?php

namespace App\Controller;

use App\Entity\Exames;
use App\Entity\Laudos;
use App\Form\LaudosType;
use App\Repository\LaudosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/laudos")
 */
class LaudosController extends AbstractController
{
    /**
     * @Route("/", name="laudos_index", methods={"GET"})
     */
    public function index(): Response
    {
        $laudos = $this->getDoctrine()
            ->getRepository(Laudos::class)
            ->findAll();

        return $this->render('laudos/index.html.twig', [
            'laudos' => $laudos,
        ]);
    }

    /**
     * @Route("/new", name="laudos_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $laudo = new Laudos();
        $qbExames = $entityManager->getRepository(Exames::class)->findAll();
        $form = $this->createForm(LaudosType::class, $laudo, array(
            'exames' => $qbExames
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $qb = $this->getDoctrine()->getRepository(Laudos::class)->findBy([
                'exame' => $laudo->getExame()
            ]);
            if ($qb) {
                $this->addFlash('error', 'O exame selecionado já possui um laudo cadastrado.');
                return $this->redirectToRoute('laudos_new');
            }
            $laudo->getDsLaudo(strip_tags($laudo->getDsLaudo()));
            $entityManager->persist($laudo);
            $entityManager->flush();
            $this->addFlash('success', 'O item foi criado com sucesso.');
            return $this->redirectToRoute('laudos_index');
        }

        return $this->render('laudos/new.html.twig', [
            'laudo' => $laudo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="laudos_show", methods={"GET"})
     */
    public function show(Laudos $laudo): Response
    {
        return $this->render('laudos/show.html.twig', [
            'laudo' => $laudo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="laudos_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Laudos $laudo): Response
    {
        $form = $this->createForm(LaudosType::class, $laudo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'O item foi editado com sucesso.');
            return $this->redirectToRoute('laudos_index');
        }

        return $this->render('laudos/edit.html.twig', [
            'laudo' => $laudo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="laudos_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Laudos $laudo): Response
    {
        if ($this->isCsrfTokenValid('delete' . $laudo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($laudo);
            $entityManager->flush();
            $this->addFlash('success', 'O item foi excluido com sucesso.');
        }

        return $this->redirectToRoute('laudos_index');
    }
}
