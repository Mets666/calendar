<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CalendarEvent;
use AppBundle\Form\CalendarEventType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $calendarEventRepository = $this->get('app.event_category.repository');
        
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $event = new CalendarEvent();
        $event->setUser($user);
        $calendarEventForm = $this->createForm(CalendarEventType::class, $event, array(
            'user' => $user
        ));

        $calendarEventForm->handleRequest($request);

        if ($calendarEventForm->isSubmitted() && $calendarEventForm->isValid()) {
            try {
                $calendarEventRepository->add($event);
            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'Unable to create category!'
                );
            }
            return $this->redirectToRoute('homepage');
        }
        
        
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'calendar_event_form' => $calendarEventForm->createView()
        ]);

    }
}
