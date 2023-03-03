<?php

namespace App\Controller;

use App\Entity\Notice;
use App\Form\NoticeType;
use App\Repository\NoticeRepository;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/notice")
 */
class NoticeController extends AbstractController
{
    /**
     * @Route("/", name="notice_index", methods={"GET"})
     * @param NoticeRepository $noticeRepository
     * @return Response
     */
    public function index(NoticeRepository $noticeRepository): Response
    {
        return $this->render('notice/index.html.twig', [
            'notices' => $noticeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="notice_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function new(Request $request): Response
    {

        $notice = new Notice();
        $form = $this->createForm(NoticeType::class, $notice);
        $form->handleRequest($request);
        $status = "error";
        $message = "";
        $employee = $this
            ->getDoctrine()
            ->getRepository(Employee::class)
            ->findOneBy(array('id' => $_POST['employee']));

        $entityManager = $this->getDoctrine()->getManager();
        $neutralHour = '00:00:00';
        $year = $_POST['year'];
        $day = $_POST['day'];
        $month = $_POST['month'];

        if ($month < 10) {
            $month = '0' . $month;
        }

        $beginAt = $year . '-' . $month . '-' . $day . ' ' . $neutralHour;
        $notice
            ->setTitle($_POST['note'])
            ->setEmployee($employee)
            ->setBeginAt(new DateTime($beginAt));
        $entityManager->persist($notice);

        try {
            $entityManager->flush();
            $status = "success";
            $message = $beginAt;
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        $response = array(
            'status' => $status,
            'message' => $message
        );

        return new JsonResponse($response);
    }

    /**
     * @Route("/{id}", name="notice_show", methods={"GET"})
     * @param Notice $notice
     * @return Response
     */
    public function show(Notice $notice): Response
    {
        return $this->render('notice/show.html.twig', [
            'notice' => $notice,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="notice_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Notice $notice
     * @return Response
     */
    public function edit(Request $request, Notice $notice): Response
    {
        $form = $this->createForm(NoticeType::class, $notice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('notice_index');
        }

        return $this->render('notice/edit.html.twig', [
            'notice' => $notice,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="notice_delete", methods={"DELETE"})
     * @param Request $request
     * @param Notice $notice
     * @return Response
     */
    public function delete(Request $request, Notice $notice): Response
    {
        if ($this->isCsrfTokenValid('delete'.$notice->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($notice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('notice_index');
    }
}
