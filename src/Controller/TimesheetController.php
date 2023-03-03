<?php

namespace App\Controller;

use App\Entity\Absence;
use App\Entity\Day;
use App\Entity\Employee;
use App\Entity\Month;
use App\Entity\OffTime;
use App\Entity\Timesheet;
use App\Entity\TimeTable;
use App\Form\AbsenceType;
use App\Form\TimesheetType;
use App\Repository\TimesheetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

/**
 * @Route("/timesheet")
 */
class TimesheetController extends AbstractController
{
    /**
     * @Route("/", name="timesheet_index", methods={"GET"})
     * @param TimesheetRepository $timesheetRepository
     * @return Response
     */
    public function index(TimesheetRepository $timesheetRepository): Response
    {
        return $this->render('timesheet/index.html.twig', [
            'timesheets' => $timesheetRepository->findAll(),
        ]);
    }

    /**
     * Calcule le nombre de jour/mois
     * @param $mois
     * @param $an
     * @return float|int
     */
    public function countDayByMonth($mois, $an)
    {
        $enmois = $an*12 + $mois;
        if (($enmois > 2037 * 12 -1) || ($enmois<1970)){return 0;}
        $an_suivant = floor(($enmois+1)/12);
        $mois_suivant = $enmois + 1 - 12 * $an_suivant;
        $duree=mktime(0, 0, 1, $mois_suivant, 1, $an_suivant)-mktime(0, 0, 1,
                $mois, 1, $an);
        return ($duree/(3600*24));
    }

    /**
     * Initialise le calendrier du mois
     * @param $month
     * @param $year
     * @return mixed
     */
    public function getMonthCalendrier($month, $year)
    {
        $debut_jour = 01;
        $debut_mois = $month;
        $debut_annee = $year;

        // Récupère le nb de jour du mois
        $fin_jour = $this->countDayByMonth($month, $year);
        $fin_mois = $month;
        $fin_annee = $year;

        $debut_date = mktime(0, 0, 0, $debut_mois, $debut_jour, $debut_annee);
        $fin_date = mktime(0, 0, 0, $fin_mois, $fin_jour, $fin_annee);

        for ($i = $debut_date; $i <= $fin_date; $i += 86400) {
            $calendrier[] = explode(" ", strtoupper(date("l F d Y", $i)));
        }


        /**
        Traduit les noms de jours ENG vers FR,
        puis les mois,
        puis renomme les clés de l'Array
         */
        for ($i = 0; $i < sizeof($calendrier); $i++) {

            switch ($calendrier[$i][0]) {
                case 'MONDAY':
                    $calendrier[$i][0] = 'LUNDI';
                    break;
                case 'TUESDAY':
                    $calendrier[$i][0] = 'MARDI';
                    break;
                case 'WEDNESDAY':
                    $calendrier[$i][0] = 'MERCREDI';
                    break;
                case 'THURSDAY':
                    $calendrier[$i][0] = 'JEUDI';
                    break;
                case 'FRIDAY':
                    $calendrier[$i][0] = 'VENDREDI';
                    break;
                case 'SATURDAY':
                    $calendrier[$i][0] = 'SAMEDI';
                    break;
                case 'SUNDAY':
                    $calendrier[$i][0] = 'DIMANCHE';
                    break;
            }

            switch ($calendrier[$i][1]) {
                case 'JANUARY':
                    $calendrier[$i][1] = 'JANVIER';
                    break;
                case 'FEBRUARY':
                    $calendrier[$i][1] = 'FEVRIER';
                    break;
                case 'MARCH':
                    $calendrier[$i][1] = 'MARS';
                    break;
                case 'APRIL':
                    $calendrier[$i][1] = 'AVRIL';
                    break;
                case 'MAY':
                    $calendrier[$i][1] = 'MAI';
                    break;
                case 'JUNE':
                    $calendrier[$i][1] = 'JUIN';
                    break;
                case 'JULY':
                    $calendrier[$i][1] = 'JUILLET';
                    break;
                case 'AUGUST':
                    $calendrier[$i][1] = 'AOUT';
                    break;
                case 'SEPTEMBER':
                    $calendrier[$i][1] = 'SEPTEMBRE';
                    break;
                case 'OCTOBER':
                    $calendrier[$i][1] = 'OCTOBRE';
                    break;
                case 'NOVEMBER':
                    $calendrier[$i][1] = 'NOVEMBRE';
                    break;
                case 'DECEMBER':
                    $calendrier[$i][1] = 'DECEMBRE';
                    break;
            }

            $calendrier[$i]['day'] = $calendrier[$i]['0'];
            unset($calendrier[$i]['0']);
            $calendrier[$i]['month'] = $calendrier[$i]['1'];
            unset($calendrier[$i]['1']);
            $calendrier[$i]['nb'] = $calendrier[$i]['2'];
            unset($calendrier[$i]['2']);
            $calendrier[$i]['year'] = $calendrier[$i]['3'];
            unset($calendrier[$i]['3']);
        }
        return $calendrier;

    }

    /**
     * @Route("/new", name="timesheet_new", methods={"GET","POST"})
     * @param Request $request
     * @return string|Response
     */
    public function new(Request $request)
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

        return $this->render('timesheet/new.html.twig', [
            'timesheet' => $timesheet,
            'form' => $form->createView(),
            'month' => $month,
            'year' => $year,
        ]);
    }

    /**
     * @Route("/newTimesheet/{id}/{month}/{year}", name="nouvelle_feuille", methods={"GET","POST"})
     * @param $id (Id de l'employé selectionné)
     * @param $month (Mois choisi ou en cours par défaut)
     * @param $year (Année choisie ou en cours par défaut)
     * @param Request $request
     * @return Response
     */
    public function newTimesheet($id, $month, $year, Request $request)
    {

        setlocale(LC_TIME, "fr_FR");

        $calendrier = $this->getMonthCalendrier($month, $year);

        $timeTable = $this
            ->getDoctrine()
            ->getRepository(TimeTable::class)
            ->findBy(array('employee' => $id));

        $employee = $this
            ->getDoctrine()
            ->getRepository(Employee::class)
            ->findOneBy(array('id' => $id));

        $absence = $this
            ->getDoctrine()
            ->getRepository(Absence::class)
            ->findAll();

        $month = $this
            ->getDoctrine()
            ->getRepository(Month::class)
            ->findOneBy(array('id' => $month));

        $ferie = $this
            ->getDoctrine()
            ->getRepository(OffTime::class)
            ->findAll();

        $testJSON = $this
            ->getDoctrine()
            ->getRepository(Timesheet::class)
            ->findOneBy(array('id' => 12));
        dump($testJSON);

        $absences = new Absence();
        $formAbs = $this->createForm(AbsenceType::class, $absences);
        $formAbs->handleRequest($request);

        if ($formAbs->isSubmitted() && $formAbs->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($absences);
            $entityManager->flush();

            return $this->redirectToRoute('time_table_new');
        }

        return $this->render('timesheet/newTimesheet.html.twig', [
            'calendrier' => $calendrier,
            'timeTable' => $timeTable,
            'absence' => $absence,
            'employee' => $employee,
            'month' => $month,
            'year' => $year,
            'feries' => $ferie,
            'formAbs' => $formAbs->createView(),
        ]);
    }

    /**
     * @Route("/sauvegarde", name="save_timesheet", methods={"GET","POST"})
     */
    public function saveTimesheet()
    {

        $status = "error";
        $message = "";

        if ($_POST) {
            try {
                $status = "success";
                $message = "OK";
            } catch (\Exception $e) {
                $message = $e->getMessage();
            }
        }else{
            $message = $_POST;
        }

        $response = array(
            'status' => $status,
            'message' => $message,
        );

        $employee = $this
            ->getDoctrine()
            ->getRepository(Employee::class)
            ->findOneBy(array('id' => $_POST['employee']));

        $month = $this
            ->getDoctrine()
            ->getRepository(Month::class)
            ->findOneBy(array('id' => $_POST['month']));


        $year = $_POST['year'];
        $datas = serialize($_POST['sheet']);

        $timesheet = new Timesheet();

        $now = new DateTime('NOW');

        $entityManager = $this->getDoctrine()->getManager();
        $timesheet
            ->setEmployee($employee)
            ->setMonth($month)
            ->setYear($year)
            ->setDatas($datas)
            ->setCreatedAt($now);

        $entityManager->persist($timesheet);
        $entityManager->flush();

        return new JsonResponse($response);

    }

    /**
     * @Route("/pdf/{id}", name="pdf", methods={"GET"})
     * @param $id
     * @return Response
     */
    public function test($id): Response
    {
        $timesheet = $this
            ->getDoctrine()
            ->getRepository(Timesheet::class)
            ->find($id);

        $days = $this
            ->getDoctrine()
            ->getRepository(Day::class)
            ->findAll();


        $timesheetUnSerialized = unserialize($timesheet->getDatas());
        dump($timesheetUnSerialized);
        dump($timesheet);
        dump($days);

        return $this->render('timesheet/show.html.twig', [
            'timesheet' => $timesheet,
            'datas' => $timesheetUnSerialized,
            'days' => $days
        ]);
    }

    /**
     * @Route("/nouvelle-absence", name="new_absence", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function newAbsence(Request $request): Response
    {
        $absence = new Absence();
        $formAbs = $this->createForm(AbsenceType::class, $absence);
        $formAbs->handleRequest($request);

        if ($formAbs->isSubmitted() && $formAbs->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($absence);
            $entityManager->flush();

            return $this->redirectToRoute('employee_new');
        }

        return $this->render('timesheet/newAbsence.html.twig', [
            'absence' => $absence,
            'formAbs' => $formAbs->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="timesheet_show", methods={"GET"})
     * @param Timesheet $timesheet
     * @return Response
     */
    public function show(Timesheet $timesheet): Response
    {
        return $this->render('timesheet/show.html.twig', [
            'timesheet' => $timesheet,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="timesheet_edit", methods={"GET","POST"})
     * @return Response
     */
    public function edit( $id ): Response
    {
        $timesheet = $this
            ->getDoctrine()
            ->getRepository(Timesheet::class)
            ->find($id);

        return $this->render('timesheet/edit.html.twig', [
            'datas' => $data = unserialize($timesheet->getDatas()),
            'employee' => $timesheet->getEmployee(),
            'month' => $timesheet->getMonth(),
            'year' =>  $timesheet->getYear(),
            'absence' => $absence = $this
                ->getDoctrine()
                ->getRepository(Absence::class)
                ->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="timesheet_delete", methods={"DELETE"})
     * @param Timesheet $timesheet
     * @return Response
     */
    public function delete(Timesheet $timesheet): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($timesheet);
            $entityManager->flush();

        return $this->redirectToRoute('home');
    }

}
