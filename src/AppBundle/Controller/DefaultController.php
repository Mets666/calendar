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
        $calendarEventRepository = $this->get('app.calendar_event.repository');
        
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $event = new CalendarEvent();
        $event->setUser($user);
        $calendarEventForm = $this->createForm(CalendarEventType::class, $event, array(
            'user' => $user
        ));

        $calendarEventForm->handleRequest($request);

        if ($calendarEventForm->isSubmitted() && $calendarEventForm->isValid()) {
            try {
//                $event->setStartDate(new \DateTime($event->getStartDate()));
//                $event->setEndDate(new \DateTime($event->getEndDate()));
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


    /**
     * @Route("/delete_event/{eventId}", name="delete_event", options = { "expose" = true })
     */
    public function deleteEventAction($eventId)
    {

        $calendarEventRepository = $this->get('app.calendar_event.repository');

        $user = $this->get('security.token_storage')->getToken()->getUser();



        try {
            $event = $calendarEventRepository->get($eventId);
            if($event->getUser() === $user) {
                $calendarEventRepository->remove($event);
            }
            else{
                throw new \Exception();
            }
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                'Unable to delete event!'
            );
            return $this->redirectToRoute('homepage');
        }
        $this->addFlash(
            'success',
            'Event successfully deleted!'
        );
        return $this->redirectToRoute('homepage');
    }
}
