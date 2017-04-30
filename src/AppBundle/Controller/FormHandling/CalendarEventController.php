<?php


namespace AppBundle\Controller\FormHandling;


use AppBundle\Entity\CalendarEvent;
use AppBundle\Form\CalendarEventType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CalendarEventController extends Controller
{
    /**
     * @Route("/add_event", name="add_event", options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addEventAction(Request $request)
    {
        $calendarEventRepository = $this->get('app.calendar_event.repository');
        $validator = $this->get('validator');
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $event = new CalendarEvent();
        $event->setUser($user);
        $calendarEventForm = $this->createForm(CalendarEventType::class, $event, array(
            'action' => $this->generateUrl('add_event'),
            'user' => $user
        ));

        $calendarEventForm->handleRequest($request);

        if ($calendarEventForm->isSubmitted()) {
            if ($calendarEventForm->isValid()) {
                try {
                    $calendarEventRepository->add($event);
                    $calendarEventRepository->save();

                } catch (\Exception $e) {
                    $this->addFlash(
                        'error',
                        'Unable to create event!'
                    );
                    return $this->redirect($request->server->get('HTTP_REFERER'));
                }
                $this->addFlash(
                    'success',
                    'Event successfully added!'
                );
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

        return $this->redirect($request->server->get('HTTP_REFERER'));
    }

    /**
     * @Route("/edit_event", name="edit_event", options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editEventAction(Request $request)
    {
        $calendarEventRepository = $this->get('app.calendar_event.repository');
        $validator = $this->get('validator');
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $formData = $request->request->get('calendar_event');

        $event = $calendarEventRepository->get($formData['id']);

        $editCalendarEventForm = $this->createForm(CalendarEventType::class, $event, array(
            'user' => $user
        ));

        $editCalendarEventForm->handleRequest($request);

        if ($editCalendarEventForm->isSubmitted()) {
            if($editCalendarEventForm->isValid()){
                try {
                    $calendarEventRepository->save();
                } catch (\Exception $e) {
                    $this->addFlash(
                        'error',
                        'Unable to edit event: Database error.'
                    );
                    return $this->redirect($request->server->get('HTTP_REFERER'));
                }
                $this->addFlash(
                    'success',
                    'Event successfully edited!'
                );
            }
            else{
                $errors = $validator->validate($event);

                foreach ($errors as  $error){
                    $this->addFlash(
                        'error',
                        'Unable to edit event: ' . $error->getMessage()
                    );
                }
            }
        }

        return $this->redirect($request->server->get('HTTP_REFERER'));
    }


    /**
     * @Route("/delete_event/{eventId}", name="delete_event", options = { "expose" = true })
     * @param Request $request
     * @param integer $eventId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteEventAction(Request $request, $eventId)
    {
        $calendarEventRepository = $this->get('app.calendar_event.repository');

        $user = $this->get('security.token_storage')->getToken()->getUser();

        try {
            /** @var \AppBundle\Entity\EventCategory $event */
            $event = $calendarEventRepository->get($eventId);
            if($event->getUser() === $user) {
                $calendarEventRepository->remove($event);
                $calendarEventRepository->save();
            }
            else{
                throw new \Exception();
            }
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                'Unable to delete event!'
            );
            return $this->redirect($request->server->get('HTTP_REFERER'));
        }
        $this->addFlash(
            'success',
            'Event successfully deleted!'
        );

        return $this->redirect($request->server->get('HTTP_REFERER'));
    }
}