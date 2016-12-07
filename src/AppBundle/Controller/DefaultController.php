<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CalendarEvent;
use AppBundle\Entity\EventCategory;
use AppBundle\Form\CalendarEventType;
use AppBundle\Form\EventCategoryFilterType;
use AppBundle\Form\EventCategoryType;
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
        $validator = $this->get('validator');
        
        $user = $this->get('security.token_storage')->getToken()->getUser();

//        $category = new EventCategory();
//        $category->setUser($user);
        $addCategoryForm = $this->createForm(EventCategoryType::class);
        $filterCategoryForm = $this->createForm(EventCategoryFilterType::class, array(), array(
            'user' => $user
        ));

        $event = new CalendarEvent();
        $event->setUser($user);
        $calendarEventForm = $this->createForm(CalendarEventType::class, $event, array(
            'user' => $user
        ));

        $editCalendarEventForm = $this->createForm(CalendarEventType::class, $event, array(
            'user' => $user
        ));

        $calendarEventForm->handleRequest($request);

        if ($calendarEventForm->isSubmitted()) {
            if ($calendarEventForm->isValid()) {
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
            else{
                $errors = $validator->validate($event);

                foreach ($errors as $error){
                    $this->addFlash(
                        'error',
                        'Unable to add event: ' . $error->getMessage()
                    );
                }
            }
        }
        
        
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'calendar_event_form' => $calendarEventForm->createView(),
            'edit_calendar_event_form' => $editCalendarEventForm->createView(),
            'add_category_form' => $addCategoryForm->createView(),
            'filter_category_form' => $filterCategoryForm->createView()
        ]);

    }

    /**
     * @Route("/edit_event", name="edit_event", options = { "expose" = true })
     */
    public function editEventAction(Request $request, $eventId)
    {

        $calendarEventRepository = $this->get('app.calendar_event.repository');
        $validator = $this->get('validator');

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $formData = $request->request->get('calendar_event');

        /** @var \AppBundle\Entity\CalendarEvent $event */
        $event = $calendarEventRepository->get($formData['id']);

        $editCalendarEventForm = $this->createForm(CalendarEventType::class, $event, array(
            'user' => $user
        ));

        $editCalendarEventForm->handleRequest($request);


    //    dump($editCalendarEventForm->isValid()); die; //check this sh*t again

        if ($editCalendarEventForm->isSubmitted()) {
            if($editCalendarEventForm->isValid()){
                try {
                    $calendarEventRepository->save($event);
                } catch (\Exception $e) {
                    $this->addFlash(
                        'error',
                        'Unable to edit event: Database error.'
                    );
                    return $this->redirectToRoute('homepage');
                }
            }
            else{
                $errors = $validator->validate($event);

                foreach ($errors as $error){
                    $this->addFlash(
                        'error',
                        'Unable to edit event: ' . $error->getMessage()
                    );
                }
            }
        }

        return $this->redirectToRoute('homepage');
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

    /**
     * @Route("/add_category", name="add_category", options = { "expose" = true })
     */
    public function addCategoryAction(Request $request)
    {
        $eventCategoryRepository = $this->get('app.event_category.repository');
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $category = new EventCategory();
        $category->setUser($user);
        $addCategoryForm = $this->createForm(EventCategoryType::class, $category);

        $addCategoryForm->handleRequest($request);
        if ($addCategoryForm->isSubmitted() && $addCategoryForm->isValid()) {
            try {
                $eventCategoryRepository->add($category);
            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'Unable to create category!'
                );
            }
            return $this->redirectToRoute('homepage');
        }

        return $this->redirectToRoute('homepage');
    }
}
