<?php

namespace App\Http\Controllers;

use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;
use MaddHatter\LaravelFullcalendar\Calendar;
use App\Event;
class EventController extends Controller
{
    //
    public function index()
    {
        $events = [];
        $data = Event::all();
        if($data->count()) {
            foreach ($data as $key => $value) {
                $events[] = Calendar::event(
                    $value->title,
                    true,
                    new \DateTime($value->start_date),
                    new \DateTime($value->end_date.' +1 day'),
                    null,
                    // Add color and link on event
                    [
                        'color' => '#f05050',
                        'url' => 'pass here url and any route',
                    ]
                );
            }
        }
        $calendar = Calendar::addEvents($events);
        return view('vendor.calendar', compact('calendar'));
    }

    public function events()
    {
        $data = [];
        $data['section'] = 'calendar';
        return view('vendor.calendar',['data' => $data]);
    }

    public function insertIntoCalendar(Request $request)
    {
        $event = new Event;
        if($request->has('title')){
            /*$event->id = $request->json('id');*/
            //I tried putting id but still the same query prob persists
            $event->title = $request->input('title');
            $event->start_date = $request->input('start');
            $event->end_date = $request->input('end');
            $event->save();
        }
        else{
            echo "Insert failed";
        }
        /*$insert->title = $request->input('title');
        $insert->start_event = $request->input('start');
        $insert->end_event = $request->input('end');*/
    }
}
