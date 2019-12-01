<?php

namespace App\Controller;

use App\Entity\Exames;
use App\Entity\Pacientes;
use App\Form\ExamesType;
use PhpParser\Node\Scalar\MagicConst\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/exames")
 */
class ExamesController extends AbstractController
{
    /**
     * @Route("/", name="exames_index", methods={"GET"})
     */
    public function index(): Response
    {
        $exames = $this->getDoctrine()
            ->getRepository(Exames::class)
            ->findAll();

        return $this->render('exames/index.html.twig', [
            'exames' => $exames,
        ]);
    }

    /**
     * @Route("/new", name="exames_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $exame = new Exames();
        if ($request->query->get('paciente')) {
            $paciente = $this->getDoctrine()->getRepository(Pacientes::class)->find($request->query->get('paciente'));
            $exame->setIdpacientes($paciente);
        }
        $form = $this->createForm(ExamesType::class, $exame);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $exameFile = $form['imagens']->getData();
            if ($exameFile) {
                $originalFilename = pathinfo($exameFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $exameFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $exameFile->move(
                        $this->getParameter('imagem_exame'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $exame->setImagens($newFilename);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($exame);
            $entityManager->flush();
            $this->addFlash('success', 'O item foi criado com sucesso.');
            return $this->redirectToRoute('exames_index');
        }

        return $this->render('exames/new.html.twig', [
            'exame' => $exame,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="exames_show", methods={"GET"})
     */
    public function show(Exames $exame): Response
    {
        return $this->render('exames/show.html.twig', [
            'exame' => $exame,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="exames_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Exames $exame): Response
    {
        $form = $this->createForm(ExamesType::class, $exame);
        $form->handleRequest($request);
        $antigoNome = $exame->getImagens();

        if ($form->isSubmitted() && $form->isValid()) {
            $exameFile = $form['imagens']->getData();
            if ($exameFile) {
                $originalFilename = pathinfo($exameFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $exameFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $exameFile->move(
                        $this->getParameter('imagem_exame'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $exame->setImagens($newFilename);
            } else {
                $exame->setImagens($antigoNome);

            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'O item foi editado com sucesso.');
            return $this->redirectToRoute('exames_index');
        }

        return $this->render('exames/edit.html.twig', [
            'exame' => $exame,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="exames_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Exames $exame): Response
    {
        if ($this->isCsrfTokenValid('delete' . $exame->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($exame);
            $entityManager->flush();
            $this->addFlash('success', 'O item foi excluido com sucesso.');
        }

        return $this->redirectToRoute('exames_index');
    }
}
