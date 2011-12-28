<?php

require_once( "iCalcreator.class.php" );

function getPirateEvents()
{

  $config = array( "unique_id" => "piratenpartei.ch" );
  $cal = new vcalendar( $config );

  $cal->setConfig( "url", "http://www.piratenpartei.ch/calendar-event/ical" );
  $cal->parse();
  $cal->sort();

  $cal->setProperty( "X-WR-TIMEZONE", "Europe/Zurich" );
  $eventArray = $cal->selectComponents(date("Y"), date("m"), date("d"), date("Y") + 1, date("m"), date("d"));

  $events = array();
  $i = 0;
  foreach( $eventArray as $year => $yearArray) 
  {
    foreach( $yearArray as $month => $monthArray ) 
    {
      foreach( $monthArray as $day => $dailyEventsArray ) 
      {
        if($i < 5)
        {
          $i++;
        }
        else 
        {
          return $events;
        }

        foreach( $dailyEventsArray as $vevent ) 
        {
            array_push($events, $vevent);
        }
      }
    }
  }
  return $events;
}


function renderDaySeperator($event, &$lastevent, $txt)
{
  if ($event->dtstart['value']['year']  == $lastevent->dtstart['value']['year'] &&
    $event->dtstart['value']['month']  == $lastevent->dtstart['value']['month'] &&
    $event->dtstart['value']['day']  == $lastevent->dtstart['value']['day'])
  {
    return "";
  }

  $lastevent = $event;
  $link = 'http://www.piratenpartei.ch/calendar-event/'.$event->dtstart['value']['year'].'-'.$event->dtstart['value']['month'].'-'.$event->dtstart['value']['day'];
  if ($event->dtstart['value']['year']  == date("Y") &&
    $event->dtstart['value']['month']  == date("n") &&
    $event->dtstart['value']['day']  == date("j"))
  {
    return '<li class="dayseparator"><a href="'.$link.'">'.$txt['calendar_today'].'</a></li>'.
           '<li class="removeme"></li>';
  }
 return '<li class="dayseparator"><a href="'.$link.'">'.$event->dtstart['value']['day'].' '.$txt['months_short'][intval($event->dtstart['value']['month'])].' '.$event->dtstart['value']['year'].'</a></li>'.
        '<li class="removeme"></li>';
}

function renderEvent($event)
{

  $curdate = $event->getProperty('x-current-dtstart');
  $time  = $curdate[1];

  iCalUtilityFunctions::transformDateTime( $time, "GMT0", "Europe/London");

  $hour = date("G",strtotime($time));
  $minute = date("i",strtotime($time));
  if (date("G", strtotime($curdate[1])) < 4)
  {
    return '<li class=""><a href="'.$event->url['value'].'">'.$event->summary['value'].'</a></li>';
  }
  return '<li class=""><a href="'.$event->url['value'].'"><span class="eventtime">'.$hour.'<span class="eventminute">'.$minute.'</span></span>'.$event->summary['value'].'</a></li>';
}

?>
