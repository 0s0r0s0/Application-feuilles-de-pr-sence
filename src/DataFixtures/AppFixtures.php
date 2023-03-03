<?php

namespace App\DataFixtures;

use App\Entity\Absence;
use App\Entity\Calendrier;
use App\Entity\Day;
use App\Entity\Employee;
use App\Entity\ExtraTime;
use App\Entity\Month;
use App\Entity\OffTime;
use App\Entity\Status;
use App\Entity\TimeTable;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadStatus($manager);
        $this->loadAbsence($manager);
        $this->loadExtraTime($manager);
        $this->loadOffTime($manager);
        $this->loadDay($manager);
        $this->loadMonth($manager);
        $this->loadEmployee($manager);
        $this->loadCalendrier($manager);
        $this->loadTimeTable($manager);

        $manager->flush();
    }

    public function loadStatus(ObjectManager $manager)
    {
        $status_names = ['employé', 'apprenti', 'stagiaire'];
        foreach ($status_names as $kn => $name) {
            $status = new Status();
            $status->setLabel($name);
            $this->setReference('status-' .$kn, $status);
            $manager->persist($status);
        }
        $manager->flush();
    }

    public function loadAbsence(ObjectManager $manager)
    {
        $absence_names = ['école', 'arrêt maladie', 'congé payé'];
        foreach ($absence_names as $kn => $name) {
            $absence = new Absence();
            $absence->setLabel($name);
            $this->setReference('absence-' .$kn, $absence);
            $manager->persist($absence);
        }
        $manager->flush();
    }

    public function loadExtraTime(ObjectManager $manager)
    {
        $data = [
            ['Maximum année', 130],
            ['Maximum semaine', 44],
            ['Maximum jour', 10],
            ['Minimum repos', 11]
        ];
        foreach ($data as $key => $datum) {
            $time = new ExtraTime();
            $time
                ->setLabel($datum[0])
                ->setDuration($datum[1]);
            $manager->persist($time);
        }
        $manager->flush();
    }

    public function loadOffTime(ObjectManager $manager)
    {
        $data = [
            ['Jour de l\'an',  new DateTime('2020/01/01')],
            ['Pâques',  new DateTime('2020/04/13')],
            ['Fête du Travail', new DateTime('2020/05/01')],
            ['Armistice 1945', new DateTime('2020/05/08')],
            ['Ascension', new DateTime('2020/05/21')],
            ['Pentecôte', new DateTime('2020/06/01')],
            ['Fête nationale', new DateTime('2020/07/14')],
            ['Assomption', new DateTime('2020/08/15')],
            ['Toussaint', new DateTime('2020/11/01')],
            ['Armistice 1918', new DateTime('2020/11/11')],
            ['Noël', new DateTime('2020/12/25')]
        ];
        foreach ($data as $key => $datum) {
            $time = new OffTime();
            $time
                ->setLabel($datum[0])
                ->setDateT($datum[1]);
            $manager->persist($time);
        }
        $manager->flush();
    }

    public function loadCalendrier(ObjectManager $manager)
    {
        $data = [
            ['Férié: Jour de l\'an', new DateTime('2020-01-01 00:00:00')],
            ['Férié: Pâques', new DateTime('2020-04-13 00:00:00')],
            ['Férié: Fête du Travail', new DateTime('2020-05-01 00:00:00')],
            ['Férié: Armistice 1945', new DateTime('2020-05-08 00:00:00')],
            ['Férié: Ascension', new DateTime('2020-05-21 00:00:00')],
            ['Férié: Pentecôte', new DateTime('2020-06-01 00:00:00')],
            ['Férié: Fête nationale', new DateTime('2020-07-14 00:00:00')],
            ['Férié: Assomption', new DateTime('2020-08-15 00:00:00')],
            ['Férié: Toussaint', new DateTime('2020-11-01 00:00:00')],
            ['Férié: Armistice 1918', new DateTime('2020-11-11 00:00:00')],
            ['Férié: Noël', new DateTime('2020-12-25 00:00:00')]
        ];

        foreach ($data as $key => $datum) {
            $date = new Calendrier();
            $date
                ->setTitle($datum[0])
                ->setBeginAt($datum[1]);
            $manager->persist($date);
        }
        $manager->flush();
    }

    public function loadMonth(ObjectManager $manager)
    {
        $month_names = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                         'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        foreach ($month_names as $kn => $names) {
            $month = new Month();
            $month->setLabel($names);
            $this->setReference('month-' .$kn, $month);
            $manager->persist($month);
        }
        $manager->flush();
    }

    public function loadDay(ObjectManager $manager)
    {
        $day_names = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        foreach ($day_names as $kn => $names) {
            $day = new Day();
            $day->setLabel($names);
            $this->setReference('day-' .$kn, $day);
            $manager->persist($day);
        }
        $manager->flush();
    }

    public function loadEmployee(ObjectManager $manager)
    {
        $employee_names = [
            ['Doyle', 'Brunson'],
            ['Robert', 'Petit'],
            ['Jacques', 'Chirac'],
            ['Karl', 'Marx'],
            ['Albert', 'Camus'],
            ['Léon', 'Tolstoï'],
            ['Christian', 'Jacq'],
            ['René', 'Barjavel']
        ];
        foreach ($employee_names as $key => $names) {
            $employee = new Employee();
            $employee
                ->setFirstName($names[0])
                ->setLastName($names[1])
                ->setIsActive(1)
                ->setStatus($this->getReference('status-' . rand(0,2)));
            $this->setReference('employee-' .$key, $employee);
            $manager->persist($employee);
        }
        $manager->flush();
    }

    public function loadTimeTable(ObjectManager $manager)
    {
        $amSdata = ['08:45:00', '09:00:00', '09:30:00', '10:00:00'];
        $amEdata = ['10:45:00', '11:30:00', '12:00:00', '12:30:00'];
        $pmSdata = ['13:00:00', '13:30:00', '14:00:00', '14:15:00'];
        $pmEdata = ['16:00:00', '17:00:00', '17:30:00', '18:00:00'];



        for ($e = 0; $e <= 7; $e ++) {
            for ($d = 0; $d <= 4; $d ++) {
                $rand = rand(0,3);
                $timeTable = new TimeTable();
                $timeTable
                    ->setEmployee($this->getReference('employee-'. $e))
                    ->setDay($this->getReference('day-'. $d))
                    ->setAmStartAt(new DateTime($amSdata[$rand]))
                    ->setAmEndAt(new DateTime($amEdata[$rand]))
                    ->setPmStartAt(new DateTime($pmSdata[$rand]))
                    ->setPmEndAt(new DateTime($pmEdata[$rand]));

                $manager->persist($timeTable);
            }
        }
        $manager->flush();
    }
}
