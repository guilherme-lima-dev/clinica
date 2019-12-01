<?php

namespace App\Controller;

use App\Entity\Acesso;
use App\Entity\Funcionarios;
use App\Form\AcessoType;
use App\Form\FuncionariosType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/funcionarios")
 */
class FuncionariosController extends AbstractController
{
    /**
     * @Route("/", name="funcionarios_index", methods={"GET"})
     */
    public function index(): Response
    {
        $funcionarios = $this->getDoctrine()
            ->getRepository(Funcionarios::class)
            ->findBy([
                'ativo' => 1
            ]);

        return $this->render('funcionarios/index.html.twig', [
            'funcionarios' => $funcionarios,
        ]);
    }

    /**
     * @Route("/new", name="funcionarios_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $funcionario = new Funcionarios();
        $form = $this->createForm(FuncionariosType::class, $funcionario);
        $form->handleRequest($request);
        $acesso = new Acesso();
        $form2 = $this->createForm(AcessoType::class, $acesso);
        $form2->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $funcionario
                ->setDataAdmissao(new \DateTime())
                ->setAtivo(1);
            $entityManager->persist($funcionario);
            $entityManager->flush();
            if ($form2->isSubmitted() && $form2->isValid()) {
                $entityManager = $this->getDoctrine()->getManagerForClass(Acesso::class);
                $acesso->setFuncionarios($funcionario);
                $acesso->setPassword(sha1(md5($acesso->getPassword())));
                $entityManager->persist($acesso);
                $entityManager->flush();
            }
            $this->addFlash('success', 'Item cadastrado com sucesso');
            return $this->redirectToRoute('funcionarios_index');
        }

        return $this->render('funcionarios/new.html.twig', [
            'funcionario' => $funcionario,
            'form' => $form->createView(),
            'form2' => $form2->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="funcionarios_show", methods={"GET"})
     */
    public function show(Funcionarios $funcionario): Response
    {
        $em = $this->getDoctrine()->getManager();
        $perfil = $em->getRepository(Acesso::class)->find($funcionario->getId());

        return $this->render('funcionarios/show.html.twig', [
            'funcionario' => $funcionario,
            'perfil' => $perfil
        ]);
    }

    /**
     * @Route("/{id}/edit", name="funcionarios_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Funcionarios $funcionario): Response
    {
        $form = $this->createForm(FuncionariosType::class, $funcionario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'O item foi editado com sucesso.');
            return $this->redirectToRoute('funcionarios_index');
        }

        return $this->render('funcionarios/edit.html.twig', [
            'funcionario' => $funcionario,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="funcionarios_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Funcionarios $funcionario): Response
    {
        if ($this->isCsrfTokenValid('delete' . $funcionario->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($funcionario);
            $entityManager->flush();
            $this->addFlash('success', 'O item foi excluido com sucesso.');
        }

        return $this->redirectToRoute('funcionarios_index');
    }

    /**
     * @Route("/desativar/{id}", name="funcionarios_desativar", methods={"GET","POST"})
     */
    public function desativar(Funcionarios $funcionario): Response
    {
        $em = $this->getDoctrine()->getManager();
        $funcionario->setAtivo(0);
        $em->persist($funcionario);
        $em->flush();
        $this->addFlash('success', 'O item foi atualizado com sucesso.');
        return $this->redirectToRoute('funcionarios_index');
    }


}
