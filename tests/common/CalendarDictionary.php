<?php

namespace tests\common;

use Carbon\Carbon;

trait CalendarDictionary
{
    protected function todayIs($date)
    {
        Carbon::setTestNow(Carbon::createFromFormat('Y-m-d', $date));
    }

    protected function nowIs($date)
    {
        Carbon::setTestNow(new Carbon($date));
    }

    protected function resetCalendar()
    {
        Carbon::setTestNow();
    }
}
