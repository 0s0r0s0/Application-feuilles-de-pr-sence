<?php


namespace App\EventSubscriber;

use App\Repository\CalendrierRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class CalendarSubscriber implements EventSubscriberInterface
{
    private $calendrierRepository;
    private $router;

    public function __construct(CalendrierRepository $calendrierRepository, UrlGeneratorInterface $router)
    {
        $this->calendrierRepository = $calendrierRepository;
        $this->router = $router;
    }


    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        // Modify the query to fit to your entity and needs
        // Change booking.beginAt by your start date property
        $events = $this->calendrierRepository
            ->createQueryBuilder('calendrier')
            ->where('calendrier.beginAt BETWEEN :start and :end OR calendrier.endAt BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
        ;

        if ($filters['user_name'] != "") {
            $events =$this->calendrierRepository
                ->createQueryBuilder('c')
                ->where('c.beginAt BETWEEN :start and :end OR c.endAt BETWEEN :start and :end')
                ->setParameter('start', $start->format('Y-m-d H:i:s'))
                ->setParameter('end', $end->format('Y-m-d H:i:s'))
                ->andWhere('c.employee = :name OR c.employee IS NULL')
                ->setParameter('name', $filters['user_name'] . '%')
                ->getQuery()
                ->getResult()
            ;
        }



        foreach ($events as $calendrier) {
            // this create the events with your data (here booking data) to fill calendar
            $calendrierEvent = new Event(
                $calendrier->getTitle(),
                $calendrier->getBeginAt(),
                $calendrier->getEndAt() // If the end date is null or not defined, a all day event is created.
            );

            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */

            $calendrierEvent->setOptions([
                'backgroundColor' => 'grey',
                'borderColor' => 'grey',
            ]);
            $calendrierEvent->addOption(
                'url',
                $this->router->generate('calendrier_show', [
                    'id' => $calendrier->getId(),
                ])
            );

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($calendrierEvent);
        }
    }
}
