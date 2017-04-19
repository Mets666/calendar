<?php

namespace AppBundle\EventListener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use AppBundle\Entity\FullcalendarEventEntity;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Tests\Controller;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class CalendarEventListener
{
    private $entityManager;
    private $tokenStorage;

    /**
     * CalendarEventListener constructor.
     * @param ManagerRegistry $doctrine
     * @param TokenStorage $tokenStorage
     */
    public function __construct(ManagerRegistry $doctrine, TokenStorage $tokenStorage)
    {
        $this->entityManager = $doctrine;
        $this->tokenStorage = $tokenStorage;
    }

    public function loadEvents(CalendarEvent $calendarEvent)
    {

        $user = $this->tokenStorage->getToken()->getUser();

        $startDate = $calendarEvent->getStartDatetime();
        $endDate = $calendarEvent->getEndDatetime();

        // load events

        $userEvents = $this->entityManager->getRepository('AppBundle:CalendarEvent')
            ->createQueryBuilder('events')
            ->where('events.startDate BETWEEN :startDate and :endDate')
            ->andWhere('events.user = :userId')
            ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
            ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'))
            ->setParameter('userId', $user->getId())
            ->getQuery()->getResult();
        
        // Create EventEntity instances and populate it's properties with data

        /** @var \AppBundle\Entity\CalendarEvent $userEvent */
        foreach($userEvents as $userEvent) {
            
            $eventEntity = new FullcalendarEventEntity($userEvent->getTitle(), $userEvent->getStartDate(), $userEvent->getEndDate());

            if($userEvent->getCategory() !== null){
                $bgColor = $userEvent->getCategory()->getColor();
                $eventEntity->setCategory($userEvent->getCategory());
            }
            else{
                $bgColor = "123456"; //Default background color
            }
            
            $eventEntity->setId($userEvent->getId());
            $eventEntity->setNote($userEvent->getNote());
            $eventEntity->setProject($userEvent->getProject());
            $eventEntity->setBgColor('#'.$bgColor);
            $eventEntity->setFgColor('#'.$this->getContrastYIQ($bgColor));
            $eventEntity->setUrl('#');
            $eventEntity->setCssClass('calendar-event');

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