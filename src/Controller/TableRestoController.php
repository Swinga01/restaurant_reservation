<?php

namespace App\Controller;

use App\Entity\TableResto;
use App\Form\TableRestoType;
use App\Repository\TableRestoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/table/resto')]
final class TableRestoController extends AbstractController
{
    #[Route(name: 'app_table_resto_index', methods: ['GET'])]
    public function index(TableRestoRepository $tableRestoRepository): Response
    {
        return $this->render('table_resto/index.html.twig', [
            'table_restos' => $tableRestoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_table_resto_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tableResto = new TableResto();
        $form = $this->createForm(TableRestoType::class, $tableResto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tableResto);
            $entityManager->flush();

            return $this->redirectToRoute('app_table_resto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('table_resto/new.html.twig', [
            'table_resto' => $tableResto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_table_resto_show', methods: ['GET'])]
    public function show(TableResto $tableResto): Response
    {
        return $this->render('table_resto/show.html.twig', [
            'table_resto' => $tableResto,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_table_resto_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TableResto $tableResto, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TableRestoType::class, $tableResto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_table_resto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('table_resto/edit.html.twig', [
            'table_resto' => $tableResto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_table_resto_delete', methods: ['POST'])]
    public function delete(Request $request, TableResto $tableResto, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tableResto->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($tableResto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_table_resto_index', [], Response::HTTP_SEE_OTHER);
    }
}
