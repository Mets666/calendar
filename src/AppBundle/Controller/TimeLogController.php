<?php


namespace AppBundle\Controller;


use AppBundle\Form\EventCategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class TimeLogController extends Controller
{
    /**
     * @Route("/time_log", name="time_log")
     */
    public function timeLogAction(Request $request)
    {
        $timeLogCategoryForm = $this->createForm(EventCategoryType::class);

        $timeLogCategoryForm->handleRequest($request);

        if ($timeLogCategoryForm->isSubmitted() && $timeLogCategoryForm->isValid()) {
            return $this->redirectToRoute('time_log');
        }

        return $this->render('default/timeLog.html.twig', array(
            'time_log_category_form' => $timeLogCategoryForm->createView()));
    }
}