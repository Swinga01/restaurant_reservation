<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Repository\TableRestoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/reservation")]
class ReservationController extends AbstractController
{
    #[Route("/", name: "app_reservation_index", methods: ["GET"])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render("reservation/index.html.twig", [
            "reservations" => $reservationRepository->findAll(),
        ]);
    }

    #[Route("/new", name: "app_reservation_new", methods: ["GET", "POST"])]
    public function new(Request $request, EntityManagerInterface $entityManager, TableRestoRepository $tableRestoRepository): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if number of people exceeds table capacity
            $tableResto = $reservation->getTableResto();
            $nombrePersonnes = $reservation->getNombrePersonnes();
            
            if ($tableResto && $nombrePersonnes > $tableResto->getCapacite()) {
                $this->addFlash("error", "Le nombre de personnes ({$nombrePersonnes}) dépasse la capacité de la table sélectionnée ({$tableResto->getCapacite()} personnes). Veuillez choisir une table plus grande ou réduire le nombre de personnes.");
                
                // Get actual table capacities from database using getCapacite()
                $tables = $tableRestoRepository->findAll();
                $tableCapacities = [];
                foreach ($tables as $table) {
                    $tableCapacities[$table->getId()] = $table->getCapacite();
                }
                
                return $this->render("reservation/new.html.twig", [
                    "reservation" => $reservation,
                    "form" => $form->createView(),
                    "tableCapacities" => $tableCapacities,
                ]);
            }
            
            $entityManager->persist($reservation);
            $entityManager->flush();

            $this->addFlash("success", "Reservation created successfully!");
            return $this->redirectToRoute("app_reservation_index", [], Response::HTTP_SEE_OTHER);
        }

        // Get actual table capacities from database using getCapacite()
        $tables = $tableRestoRepository->findAll();
        $tableCapacities = [];
        foreach ($tables as $table) {
            $tableCapacities[$table->getId()] = $table->getCapacite(); // French method name
        }

        return $this->render("reservation/new.html.twig", [
            "reservation" => $reservation,
            "form" => $form->createView(),
            "tableCapacities" => $tableCapacities,
        ]);
    }

    #[Route("/{id}", name: "app_reservation_show", methods: ["GET"])]
    public function show(Reservation $reservation): Response
    {
        return $this->render("reservation/show.html.twig", [
            "reservation" => $reservation,
        ]);
    }

    #[Route("/{id}/edit", name: "app_reservation_edit", methods: ["GET", "POST"])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager, TableRestoRepository $tableRestoRepository): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if number of people exceeds table capacity
            $tableResto = $reservation->getTableResto();
            $nombrePersonnes = $reservation->getNombrePersonnes();
            
            if ($tableResto && $nombrePersonnes > $tableResto->getCapacite()) {
                $this->addFlash("error", "Le nombre de personnes ({$nombrePersonnes}) dépasse la capacité de la table sélectionnée ({$tableResto->getCapacite()} personnes). Veuillez choisir une table plus grande ou réduire le nombre de personnes.");
                
                // Get table capacities for the form
                $tables = $tableRestoRepository->findAll();
                $tableCapacities = [];
                foreach ($tables as $table) {
                    $tableCapacities[$table->getId()] = $table->getCapacite();
                }
                
                return $this->render("reservation/edit.html.twig", [
                    "reservation" => $reservation,
                    "form" => $form->createView(),
                    "tableCapacities" => $tableCapacities,
                ]);
            }
            
            $entityManager->flush();
            $this->addFlash("success", "Reservation updated successfully!");
            return $this->redirectToRoute("app_reservation_index", [], Response::HTTP_SEE_OTHER);
        }

        // Get table capacities for the form
        $tables = $tableRestoRepository->findAll();
        $tableCapacities = [];
        foreach ($tables as $table) {
            $tableCapacities[$table->getId()] = $table->getCapacite();
        }

        return $this->render("reservation/edit.html.twig", [
            "reservation" => $reservation,
            "form" => $form->createView(),
            "tableCapacities" => $tableCapacities,
        ]);
    }

    #[Route("/{id}", name: "app_reservation_delete", methods: ["POST"])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid("delete" . $reservation->getId(), $request->request->get("_token"))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
            $this->addFlash("success", "Reservation deleted successfully!");
        }

        return $this->redirectToRoute("app_reservation_index", [], Response::HTTP_SEE_OTHER);
    }
}