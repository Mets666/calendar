<?php
/**
 * Created by PhpStorm.
 * User: Matej Sadloň
 * Date: 14.1.2017
 * Time: 16:04
 */

namespace AppBundle\Controller;

use AppBundle\Form\CalendarEventType;
use AppBundle\Form\EventCategoryFilterType;
use AppBundle\Form\EventCategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomepageController extends DefaultController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $filterCategoryForm = $this->createForm(EventCategoryFilterType::class, array(), array(
            'user' => $user
        ));

        $categoryForm = $this->createForm(EventCategoryType::class, array(), array(
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
            'category_form' => $categoryForm->createView(),
            'filter_category_form' => $filterCategoryForm->createView()
        ]);

    }
}