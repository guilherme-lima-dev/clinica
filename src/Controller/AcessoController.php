<?php

namespace App\Controller;

use App\Entity\Acesso;
use App\Entity\Funcionarios;
use App\Form\AcessoType;
use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/acesso")
 */
class AcessoController extends AbstractController
{
    /**
     * @Route("/", name="acesso_index", methods={"GET"})
     */
    public function index(): Response
    {
        $acessos = $this->getDoctrine()
            ->getRepository(Acesso::class)
            ->findAll();

        return $this->render('acesso/index.html.twig', [
            'acessos' => $acessos,
        ]);
    }

    /**
     * @Route("/new", name="acesso_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $acesso = new Acesso();
        $form = $this->createForm(AcessoType::class, $acesso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $acesso->setPassword(sha1(md5($acesso->getPassword())));
            $entityManager->persist($acesso);
            $entityManager->flush();
            $this->addFlash('success', 'O item foi criado com sucesso.');
            return $this->redirectToRoute('acesso_index');
        }

        return $this->render('acesso/new.html.twig', [
            'acesso' => $acesso,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="acesso_show", methods={"GET"})
     */
    public function show(Acesso $acesso): Response
    {
        return $this->render('acesso/show.html.twig', [
            'acesso' => $acesso,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="acesso_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Acesso $acesso): Response
    {
        $form = $this->createForm(AcessoType::class, $acesso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $acesso->setPassword(sha1(md5($acesso->getPassword())));
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'O item foi editado com sucesso.');
            return $this->redirectToRoute('acesso_index');
        }

        return $this->render('acesso/edit.html.twig', [
            'acesso' => $acesso,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="acesso_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Acesso $acesso): Response
    {
        if ($this->isCsrfTokenValid('delete' . $acesso->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($acesso);
            $entityManager->flush();
        }
        $this->addFlash('success', 'O item foi excluído com sucesso.');
        return $this->redirectToRoute('acesso_index');
    }

    /**
     * @Route("/change/password", name="change_password", methods={"GET","POST"})
     */
    public function changePassword(Request $request): Response
    {
        if ($request->getMethod() == 'GET') {
            return $this->render('acesso/changePassword.html.twig', array());
        } elseif ($request->getMethod() == 'POST') {

            $entityManager = $this->getDoctrine()->getManager();
            $antigaSenha = $request->request->get('senhaAntiga');
            $senha = $request->request->get('senhaNova');
            $confirmaSenha = $request->request->get('senhaConfirmada');

            if (sha1(md5($antigaSenha)) == $this->getUser()->getPassword()) {
                if ($senha == $confirmaSenha) {
                    $this->getUser()->setPassword(sha1(md5($senha)));
                    $entityManager->merge($this->getUser());
                    $entityManager->flush();
                    $this->addFlash('success', 'Senha alterada com sucesso!');
                } else {
                    $this->addFlash('error', 'As senhas de confirmação não correspondem!');
                    return $this->redirectToRoute('change_password');
                }
            } else {
                $this->addFlash('error', 'A senha antiga não confere!');
                return $this->redirectToRoute('change_password');
            }

        }
        return $this->redirectToRoute('consultas_index');

    }
}
