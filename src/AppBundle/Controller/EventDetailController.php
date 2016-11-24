<?php


namespace AppBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EventDetailController
{
    /**
     * @Route("/event_detail:{eventId}", name="event_detail")
     */
    public function eventDetailAction(Request $request, $eventId)
    {

    }
}