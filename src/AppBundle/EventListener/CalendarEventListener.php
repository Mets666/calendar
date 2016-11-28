<?php

namespace AppBundle\EventListener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Tests\Controller;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class CalendarEventListener
{
    private $entityManager;
    private $token_storage;

    public function __construct(ManagerRegistry $doctrine, TokenStorage $token_storage)
    {
        $this->entityManager = $doctrine;
        $this->token_storage = $token_storage;
    }

    public function loadEvents(CalendarEvent $calendarEvent)
    {

        $user = $this->token_storage->getToken()->getUser();

        $startDate = $calendarEvent->getStartDatetime();
        $endDate = $calendarEvent->getEndDatetime();

        // The original request so you can get filters from the calendar
        // Use the filter in your query for example

        $request = $calendarEvent->getRequest();
        $filter = $request->get('filter');




        // load events using your custom logic here,
        // for instance, retrieving events from a repository

        $userEvents = $this->entityManager->getRepository('AppBundle:CalendarEvent')
            ->createQueryBuilder('events')
            ->where('events.startDate BETWEEN :startDate and :endDate')
            ->andWhere('events.user = :userId')
            ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
            ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'))
            ->setParameter('userId', $user->getId())
            ->getQuery()->getResult();

//        dump($calendarEvents); die;

        // $companyEvents and $companyEvent in this example
        // represent entities from your database, NOT instances of EventEntity
        // within this bundle.
        //
        // Create EventEntity instances and populate it's properties with data
        // from your own entities/database values.

        /** @var \AppBundle\Entity\CalendarEvent $userEvent */
        foreach($userEvents as $userEvent) {

            // create an event with a start/end time, or an all day event
//            if ($calendarEvent->getAllDayEvent() === false) {
                $eventEntity = new EventEntity($userEvent->getTitle(), $userEvent->getStartDate(), $userEvent->getEndDate());
//            } else {
//                $eventEntity = new EventEntity($calendarEvent->getTitle(), $calendarEvent->getStartDatetime(), null, true);
//            }

            if($userEvent->getCategory() !== null){
                $bgColor = $userEvent->getCategory()->getColor();
                $eventEntity->setCategory($userEvent->getCategory()->getTitle());
            }
            else{
                $bgColor = "123456"; //Default background color
            }

            //optional calendar event settings
//            $eventEntity->setAllDay(true); // default is false, set to true if this is an all day event
            
            $eventEntity->setBgColor('#'.$bgColor); //set the background color of the event's label
            $eventEntity->setFgColor('#'.$this->getContrastYIQ($bgColor)); //set the foreground color of the event's label with contrast to background
            $eventEntity->setUrl('#'); // url to send user to when event label is clicked
            $eventEntity->setCssClass('calendar-event'); // a custom class you may want to apply to event labels

            //finally, add the event to the CalendarEvent for displaying on the calendar
            $calendarEvent->addEvent($eventEntity);
        }
    }

    private function getContrastYIQ($hexcolor){
        $r = hexdec(substr($hexcolor,0,2));
        $g = hexdec(substr($hexcolor,2,2));
        $b = hexdec(substr($hexcolor,4,2));
        $yiq = (($r*299)+($g*587)+($b*114))/1000;
        return ($yiq >= 128) ? '000000' : 'FFFFFF';
    }
}