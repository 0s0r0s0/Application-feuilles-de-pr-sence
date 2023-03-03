<?php

namespace App\Controller;

use App\Entity\Absence;
use App\Form\Absence1Type;
use App\Form\AbsenceType;
use App\Repository\AbsenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * CRUD absence -> Index(liste), New(Créer), Show(Voir), Edit(Modifier), Delete(Supprimer)
 * @Route("/absence")
 */
class AbsenceController extends AbstractController
{
    /**
     * @Route("/", name="absence_index", methods={"GET"})
     * @param AbsenceRepository $absenceRepository
     * @return Response
     */
    public function index(AbsenceRepository $absenceRepository): Response
    {
        return $this->render('absence/index.html.twig', [
            'absences' => $absenceRepository->findAll(),
        ]);
    }

    /**
     * Formulaire de création de nouvelles absences
     * @Route("/new", name="absence_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $absence = new Absence();
        $form = $this->createForm(AbsenceType::class, $absence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($absence);
            $entityManager->flush();

            return $this->redirectToRoute('absence_index');
        }

        return $this->render('absence/new.html.twig', [
            'absence' => $absence,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="absence_show", methods={"GET"})
     * @param Absence $absence
     * @return Response
     */
    public function show(Absence $absence): Response
    {
        return $this->render('absence/show.html.twig', [
            'absence' => $absence,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="absence_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Absence $absence
     * @return Response
     */
    public function edit(Request $request, Absence $absence): Response
    {
        $form = $this->createForm(AbsenceType::class, $absence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('edit_param');
        }

        return $this->render('absence/edit.html.twig', [
            'absence' => $absence,
            'formAbs' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="absence_delete", methods={"DELETE"})
     * @param Request $request
     * @param Absence $absence
     * @return Response
     */
    public function delete(Request $request, Absence $absence): Response
    {
        if ($this->isCsrfTokenValid('delete'.$absence->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($absence);
            $entityManager->flush();
        }

        return $this->redirectToRoute('edit_param');
    }
}
