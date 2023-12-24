<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class SchedulePostEvent extends Event
{
    const NAME = 'schedule.post';


}