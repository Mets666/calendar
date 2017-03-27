<?php
/**
 * Created by PhpStorm.
 * User: Matej Sadloň
 * Date: 14.1.2017
 * Time: 16:04
 */

namespace AppBundle\Controller;

use AppBundle\Form\CalendarEventType;
use AppBundle\Form\EventFilterType;
use AppBundle\Form\EventCategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomepageController extends DefaultController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepageAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $eventFilterForm = $this->createForm(EventFilterType::class, array(), array(
            'user' => $user
        ));

        $addCategoryForm = $this->createForm(EventCategoryType::class, array(), array(
            'action' => $this->generateUrl('add_category')
        ));

        $calendarEventForm = $this->createForm(CalendarEventType::class, array(), array(
            'action' => $this->generateUrl('add_event'),
            'user' => $user
        ));

        $editCalendarEventForm = $this->createForm(CalendarEventType::class, array(), array(
            'action' => $this->generateUrl('edit_event'),
            'user' => $user
        ));

        return $this->render('default/index.html.twig', [
            'calendar_event_form' => $calendarEventForm->createView(),
            'edit_calendar_event_form' => $editCalendarEventForm->createView(),
            'add_category_form' => $addCategoryForm->createView(),
            'event_filter_form' => $eventFilterForm->createView()
        ]);

    }
}