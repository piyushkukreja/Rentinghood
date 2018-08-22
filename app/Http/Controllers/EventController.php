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
        $data = [];
        $data['section'] = 'calendar';
        return view('vendor.calendar',['data' => $data]);
    }

    public function events()
    {
        return \Auth::user()->events;
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
