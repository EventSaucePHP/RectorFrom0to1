<?php

use EventSauce\EventSourcing\Time\Clock;
use EventSauce\EventSourcing\Time\SystemClock;

function create_clock(): Clock
{
    return new SystemClock();
}
