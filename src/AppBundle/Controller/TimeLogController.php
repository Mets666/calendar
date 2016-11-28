<?php


namespace AppBundle\Controller;


use AppBundle\Entity\EventCategory;
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
        $eventCategoryRepository = $this->get('app.event_category.repository');

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $category = new EventCategory();
        $category->setUser($user);
        $timeLogCategoryForm = $this->createForm(EventCategoryType::class, $category);

        $timeLogCategoryForm->handleRequest($request);

        if ($timeLogCategoryForm->isSubmitted() && $timeLogCategoryForm->isValid()) {
            try {
                $eventCategoryRepository->add($category);
            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'Unable to create category!'
                );
            }
            return $this->redirectToRoute('time_log');
        }

        $bgColor = "#DBF1A1";
        $r = hexdec(substr($bgColor,0,2));
        $g = hexdec(substr($bgColor,2,2));
        $b = hexdec(substr($bgColor,4,2));
        $contrast = 1 - ( 0.299 * $r + 0.587 * $g + 0.114 * $b)/255;
        if($contrast < 0.5){$textColor = '#000000';} else{$textColor = '#FFFFFF';}

        dump($textColor); die();

        return $this->render('default/timeLog.html.twig', array(
            'time_log_category_form' => $timeLogCategoryForm->createView()));
    }
}