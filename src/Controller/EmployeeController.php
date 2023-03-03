<?php

namespace App\Controller;

use App\Entity\Absence;
use App\Entity\Employee;
use App\Entity\Job;
use App\Entity\Status;
use App\Entity\Timesheet;
use App\Entity\TimeTable;
use App\Form\AbsenceType;
use App\Form\EmployeeType;
use App\Form\JobType;
use App\Form\StatusType;
use App\Form\TimesheetType;
use App\Repository\EmployeeRepository;
use App\Repository\TimesheetRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class EmployeeController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET", "POST"})
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param EmployeeRepository $employeeRepository
     * @param TimesheetRepository $timesheetRepository
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request, EmployeeRepository $employeeRepository, TimesheetRepository $timesheetRepository): Response
    {
        $timesheet = new Timesheet();
        $form = $this->createForm(TimesheetType::class, $timesheet);
        $form->handleRequest($request);

        $month = strval(intval(date("m")));
        $year = strval(intval(date("Y")));

        if ($form->isSubmitted() && $form->isValid()) {

            return $this->redirectToRoute('nouvelle_feuille', [
                'id' => $timesheet->getEmployee()->getId(),
                'month' => $timesheet->getMonth()->getId(),
                'year' => $timesheet->getYear(),
            ]);
        }
        $employee = $this
            ->getDoctrine()
            ->getRepository(Employee::class)
            ->findAll();

        $employees = $paginator->paginate(
            $employeeRepository->findAllSortedByService(),
            $request->query->getInt('page',1),8);

        $sheets = [];
        foreach ($employee as $item){
            $data = $this->getDoctrine()->getRepository(Timesheet::class)->findOneByEmployeeSortedByDate($item);

             if (isset($data)) {
                $sheets[] = $data;
            }
        }

        $time_tables = $this
            ->getDoctrine()
            ->getRepository(TimeTable::class)
            ->findAll();

        return $this->render('employee/index.html.twig', [
            'employees' => $employees,
            'timesheet' => $timesheet,
            'form' => $form->createView(),
            'month' => $month,
            'year' => $year,
            'sheets' => $sheets,
            'timetables' => $time_tables
        ]);
    }

    /**
     * @Route("salariés/new", name="employee_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $employee = new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $employee->setIsActive(1);
            $entityManager->persist($employee);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        $status = new Status();
        $formStatus = $this->createForm(StatusType::class, $status);
        $formStatus->handleRequest($request);

        if ($formStatus->isSubmitted() && $formStatus->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($status);
            $entityManager->flush();

            return $this->redirectToRoute('employee_new');
        }

        $job = new Job();
        $formJob = $this->createForm(JobType::class, $job);
        $formJob->handleRequest($request);

        if ($formJob->isSubmitted() && $formJob->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($job);
            $entityManager->flush();

            return $this->redirectToRoute('employee_new');
        }

        return $this->render('employee/new.html.twig', [
            'employee' => $employee,
            'form' => $form->createView(),
            'formStatus' => $formStatus->createView(),
            'formJob' => $formJob->createView(),
        ]);
    }

    /**
     * @Route("salariés/nouveau-statut", name="new_status", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function newStatus(Request $request): Response
    {
        $status = new Status();
        $formStatus = $this->createForm(StatusType::class, $status);
        $formStatus->handleRequest($request);

        if ($formStatus->isSubmitted() && $formStatus->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($status);
            $entityManager->flush();

        }

        return $this->render('employee/newStatus.html.twig', [
            'status' => $status,
            'formStatus' => $formStatus->createView(),
        ]);
    }

    /**
     * @Route("salariés/modifier-paramètres", name="edit_param", methods={"GET","POST"} )
     * @param Request $request
     * @return Response
     */
    public function editParam(Request $request): Response
    {
        $status = new Status();
        $formStatus = $this->createForm(StatusType::class, $status);
        $formStatus->handleRequest($request);

        if ($formStatus->isSubmitted() && $formStatus->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($status);
            $entityManager->flush();

        }

        $job = new Job();
        $formJob = $this->createForm(JobType::class, $job);
        $formJob->handleRequest($request);

        if ($formJob->isSubmitted() && $formJob->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($job);
            $entityManager->flush();

        }

        $absence = new Absence();
        $formAbs = $this->createForm(AbsenceType::class, $absence);
        $formAbs->handleRequest($request);

        if ($formAbs->isSubmitted() && $formAbs->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($absence);
            $entityManager->flush();

        }


        return $this->render('employee/edit_param.html.twig', [
            'formStatus' => $formStatus->createView(),
            'formJob' => $formJob->createView(),
            'formAbs' => $formAbs->createView(),
            'jobs' => $jobFind = $this->getDoctrine()->getRepository(Job::class)->findAll(),
            'services' => $statusFind = $this->getDoctrine()->getRepository(Status::class)->findAll(),
            'absences' => $absencefind = $this->getDoctrine()->getRepository(Absence::class)->findAll()
        ]);
    }

    /**
     * @Route("salariés/{id}", name="employee_show", methods={"GET"})
     * @param Employee $employee
     * @return Response
     */
    public function show(Employee $employee): Response
    {
        $employees = $this
            ->getDoctrine()
            ->getRepository(Employee::class)
            ->findAll();

        $sheets = [];
        foreach ($employees as $item){
            $data = $this->getDoctrine()->getRepository(Timesheet::class)->findlast6ByEmployeeSortedByDate($item);

            if (isset($data)) {
                $sheets[] = $data;
            }
        }
    dump($sheets);
        return $this->render('employee/show.html.twig', [
            'employee' => $employee,
            'sheets' => $sheets,
        ]);
    }

    /**
     * @Route("salariés/{id}/edit", name="employee_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Employee $employee
     * @return Response
     */
    public function edit(Request $request, Employee $employee): Response
    {
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home');
        }

        $status = new Status();
        $formStatus = $this->createForm(StatusType::class, $status);
        $formStatus->handleRequest($request);

        if ($formStatus->isSubmitted() && $formStatus->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($status);
            $entityManager->flush();

        }

        $job = new Job();
        $formJob = $this->createForm(JobType::class, $job);
        $formJob->handleRequest($request);

        if ($formJob->isSubmitted() && $formJob->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($job);
            $entityManager->flush();

        }

        return $this->render('employee/edit.html.twig', [
            'employee' => $employee,
            'form' => $form->createView(),
            'formStatus' => $formStatus->createView(),
            'formJob' => $formJob->createView(),
        ]);
    }

    /**
     * @Route("salariés/{id}", name="employee_delete", methods={"DELETE"})
     * @param Request $request
     * @param Employee $employee
     * @return Response
     */
    public function delete(Request $request, Employee $employee): Response
    {
        $tables = $this
            ->getDoctrine()
            ->getRepository(TimeTable::class)
            ->findBy(array('employee' => $employee));

        if ($tables) {
            foreach ($tables as $table){

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($table);
                $entityManager->flush();
            }
        }

        if ($this->isCsrfTokenValid('delete'.$employee->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($employee);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home');
    }


}
