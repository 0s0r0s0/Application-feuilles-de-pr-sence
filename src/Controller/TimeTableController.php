<?php

namespace App\Controller;

use App\Entity\Absence;
use App\Entity\Day;
use App\Entity\Employee;
use App\Entity\TimeTable;
use App\Form\AbsenceType;
use App\Form\TimeTableType;
use App\Repository\AbsenceRepository;
use App\Repository\DayRepository;
use App\Repository\MonthRepository;
use App\Repository\TimeTableRepository;
use DateTime;
use Doctrine\ORM\Mapping\Id;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * CRUD absence -> Index(liste), New(Créer), Show(Voir), Edit(Modifier), Delete(Supprimer), Save(Enregistrer)
 * @Route("/time/table")
 */
class TimeTableController extends AbstractController
{
    /**
     * @Route("/", name="time_table_index", methods={"GET"})
     * @param TimeTableRepository $timeTableRepository
     * @return Response
     */
    public function index(TimeTableRepository $timeTableRepository): Response
    {
        return $this->render('time_table/index.html.twig', [
            'time_tables' => $timeTableRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="time_table_new", methods={"GET","POST"})
     * @param Request $request
     * @param Id $id
     * @return Response
     */
    public function new(Request $request, $id): Response
    {
        $time_table = new TimeTable();
        $form = $this->createForm(TimeTableType::class, $time_table);
        $form->handleRequest($request);

        $day = $this->getDoctrine()
            ->getRepository(Day::class)
            ->findAll();

        $absences = $this->getDoctrine()
            ->getRepository(Absence::class)
            ->findAll();

        $absence = new Absence();
        $formAbs = $this->createForm(AbsenceType::class, $absence);
        $formAbs->handleRequest($request);

        if ($formAbs->isSubmitted() && $formAbs->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($absence);
            $entityManager->flush();

            return $this->redirectToRoute('time_table_new');
        }

        return $this->render('time_table/new.html.twig', [
            'form' => $form->createView(),
            'formAbs' => $formAbs->createView(),
            'absence' => $absences,
            'days' => $day,
            'employee' => $employee = $this->getDoctrine()->getRepository(Employee::class)->findOneBy(array('id' => $id))
        ]);
    }

    /**
     * @Route("/delete/{id}", name="time_table_delete", methods={"GET","POST"})
     * @param $id (Id de l'employé selectionné)
     * @return Response
     */
    public function delete($id): Response
    {
        $tables = $this
            ->getDoctrine()
            ->getRepository(TimeTable::class)
            ->findBy(array('employee' => $id));

        if ($tables) {
            foreach ($tables as $table){

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($table);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('time_table_new', ['id' => $id] );
    }

    /**
     * @Route("/save", name="time_table_save", methods={"GET", "POST"})
     * @return Response
     * @throws Exception
     */
    public function save(): Response
    {
        $status = "error";
        $message = "";

        if ($_POST) {
            try {
                $status = "success";
                $message = $_POST;
            } catch (Exception $e) {
                $message = $e->getMessage();
            }
        }else{
            $message = $_POST;
        }

        $response = array(
            'status' => $status,
            'message' => $message
        );

        $tables = $_POST['table'];

        $employee = $this
            ->getDoctrine()
            ->getRepository(Employee::class)
            ->findOneBy(array('id' => $_POST['employee']));

        for ($i = 1; $i <= 5; $i ++) {

            $day = $this
                ->getDoctrine()
                ->getRepository(Day::class)
                ->findOneBy(array('id' => $i));

            $timeTable = new TimeTable();

            $entityManager = $this->getDoctrine()->getManager();

                    if (!(empty($tables[$i][0]))) {
                        $absence = $this
                            ->getDoctrine()
                            ->getRepository(Absence::class)
                            ->findOneBy(array('id' => $tables[$i][0]));
                        $timeTable
                            ->setAbsence($absence);
                    }

                    if (!(empty($tables[$i][1]))) {

                        $timeTable
                            ->setAmStartAt(new DateTime($tables[$i][1]));
                    }
                    if (!(empty($tables[$i][2]))) {
                        $timeTable
                            ->setAmEndAt(new DateTime($tables[$i][2]));
                    }
                    if (!(empty($tables[$i][3]))) {
                        $timeTable
                            ->setPmStartAt(new DateTime($tables[$i][3]));
                    }
                    if (!(empty($tables[$i][4]))) {
                        $timeTable
                            ->setPmEndAt(new DateTime($tables[$i][4]));
                    }


                $timeTable
                ->setDay($day)
                ->setEmployee($employee);
            $entityManager->persist($timeTable);
            $entityManager->flush();
        }

        return new JsonResponse($response);

    }


    /**
     * @Route("/{id}", name="time_table_show", methods={"GET"})
     * @param TimeTable $timeTable
     * @return Response
     */
    public function show(TimeTable $timeTable): Response
    {
        return $this->render('time_table/show.html.twig', [
            'time_table' => $timeTable,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="time_table_edit", methods={"GET","POST"})
     * @param Request $request
     * @param TimeTable $timeTable
     * @return Response
     */
    public function edit(Request $request, TimeTable $timeTable): Response
    {
        $form = $this->createForm(TimeTableType::class, $timeTable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('time_table_index');
        }

        return $this->render('time_table/edit.html.twig', [
            'time_table' => $timeTable,
            'form' => $form->createView(),
        ]);
    }


}
