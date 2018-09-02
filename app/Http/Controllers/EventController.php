<?php

namespace App\Http\Controllers;

use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;
use MaddHatter\LaravelFullcalendar\Calendar;
use App\Event;
class EventController extends Controller
{
    public function index()
    {
        $data = [];
        $data['section'] = 'calendar';
        return view('vendor.calendar',['data' => $data]);
    }

    public function eventsShowAll()
    {
        return \Auth::user()->events;
    }

    public function update(Request $request, $id)
    {
        $response = [];
        $event = Event::findOrFail($id);
        $event->title = $request->input('title');
        $event->date = $request->input('date');
        $event->color = $request->input('color');
        $event->save();
        $response['event'] = $event;
        $response['status'] = 'success';
        return $response;
    }

    public function destroy($id) {
        $response = [];
        $event = Event::findOrFail($id);
        $event->delete();
        $response['status'] = 'success';
        return $response;
    }
}
