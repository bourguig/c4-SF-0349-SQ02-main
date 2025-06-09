<?php

namespace App\Controller;

use App\Entity\Satisfaction;
use App\Form\SatisfactionForm;
use App\Repository\SatisfactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/satisfaction')]
final class SatisfactionController extends AbstractController
{
    #[Route(name: 'app_satisfaction_index', methods: ['GET'])]
    public function index(SatisfactionRepository $satisfactionRepository): Response
    {
        return $this->render('satisfaction/index.html.twig', [
            'satisfactions' => $satisfactionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_satisfaction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $satisfaction = new Satisfaction();
        $form = $this->createForm(SatisfactionForm::class, $satisfaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($satisfaction);
            $entityManager->flush();

            return $this->redirectToRoute('app_satisfaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('satisfaction/new.html.twig', [
            'satisfaction' => $satisfaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_satisfaction_show', methods: ['GET'])]
    public function show(Satisfaction $satisfaction): Response
    {
        return $this->render('satisfaction/show.html.twig', [
            'satisfaction' => $satisfaction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_satisfaction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Satisfaction $satisfaction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SatisfactionForm::class, $satisfaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_satisfaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('satisfaction/edit.html.twig', [
            'satisfaction' => $satisfaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_satisfaction_delete', methods: ['POST'])]
    public function delete(Request $request, Satisfaction $satisfaction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$satisfaction->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($satisfaction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_satisfaction_index', [], Response::HTTP_SEE_OTHER);
    }
}
