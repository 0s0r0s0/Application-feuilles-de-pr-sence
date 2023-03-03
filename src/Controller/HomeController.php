<?php


namespace App\Controller;


use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use App\Repository\StatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;


class HomeController extends AbstractController
{
    /**
     * Gére l'affichage du calendrier
     * @Route("/calendar", name="calendar")
     * @param EmployeeRepository $employeeRepository
     * @param StatusRepository $statusRepository
     * @return Response
     */
    public function calendar(EmployeeRepository $employeeRepository, StatusRepository $statusRepository): Response
    {

        return $this->render('calendrier/calendar.html.twig', [
            'employees' => $employeeRepository->findAll(),
            'status' => $statusRepository->findAll(),
        ]);
    }

}