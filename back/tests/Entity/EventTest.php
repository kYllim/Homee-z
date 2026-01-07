<?php

namespace App\Tests\Entity;

use App\Entity\Event;
use App\Entity\Person;
use App\Entity\Household;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testPeutCreerEvenement(): void
    {
        $event = new Event();
        $event->setTitle('Test Event');
        $event->setDescription('Test Description');
        $event->setType('meeting');
        
        $this->assertEquals('Test Event', $event->getTitle());
        $this->assertEquals('Test Description', $event->getDescription());
        $this->assertEquals('meeting', $event->getType());
    }

    public function testPeutFixerDates(): void
    {
        $event = new Event();
        $startDate = new \DateTimeImmutable('2026-01-10 10:00:00');
        $endDate = new \DateTimeImmutable('2026-01-10 11:00:00');
        
        $event->setStartAt($startDate);
        $event->setEndAt($endDate);
        
        $this->assertEquals($startDate, $event->getStartAt());
        $this->assertEquals($endDate, $event->getEndAt());
    }

    public function testPeutFixerStatut(): void
    {
        $event = new Event();
        
        $event->setStatus('prévu');
        $this->assertEquals('prévu', $event->getStatus());
        
        $event->setStatus('en cours');
        $this->assertEquals('en cours', $event->getStatus());
        
        $event->setStatus('terminé');
        $this->assertEquals('terminé', $event->getStatus());
    }

    public function testPeutAssocierCreateur(): void
    {
        $event = new Event();
        $person = new Person();
        $person->setFirstName('John');
        $person->setLastName('Doe');
        
        $event->setCreator($person);
        
        $this->assertSame($person, $event->getCreator());
    }

    public function testPeutAssocierFoyer(): void
    {
        $event = new Event();
        $household = new Household();
        $household->setName('Test Household');
        $household->setAccessCode('TEST123');
        
        $event->setHousehold($household);
        
        $this->assertSame($household, $event->getHousehold());
    }

    public function testCreatedAtImmuable(): void
    {
        $event = new Event();
        $now = new \DateTimeImmutable();
        
        $event->setCreatedAt($now);
        
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->getCreatedAt());
        $this->assertEquals($now->getTimestamp(), $event->getCreatedAt()->getTimestamp());
    }
}
