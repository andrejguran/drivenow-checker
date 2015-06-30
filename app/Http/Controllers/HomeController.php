<?php

namespace DriveNowChecker\Http\Controllers;

use DriveNowChecker\Checkers\Checker;
use DriveNowChecker\Watcher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Symfony\Component\Routing\Matcher\RedirectableUrlMatcher;

class HomeController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
    */
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(\Illuminate\Http\Request $request)
    {
        $watchers = Watcher::where('user_id', Auth::user()->id)->get();
        return view('home', compact('watchers'));
    }

    public function settings()
    {
        return view('settings');
    }

    public function postSettings(\Illuminate\Http\Request $request)
    {
        $update = [
            'email' => $request->input('email'),
            'city' => $request->input('city')
        ];

        if ($request->input('password')) {
            $update['password'] = bcrypt($request->input('password'));
        }

        DB::table('users')
            ->where('id', Auth::user()->id)
            ->update($update);

        $request->session()->flash('info', ['Edit was successful!']);

        return redirect('/settings');
    }

    public function createWatcher(\Illuminate\Http\Request $request)
    {
        $watcher = Request::all();
        $watcher['user_id'] = Auth::user()->id;

        $googleApiKey = Config::get('drivenowchecker.google-api-key');
        $json = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($watcher['address'])."&sensor=false&key={$googleApiKey}"));
        $location = $json->results[0]->geometry->location;
        $watcher['latitude'] = $location->lat;
        $watcher['longtitude'] = $location->lng;

        if (empty($watcher['refresh_period'])) $watcher['refresh_period'] = 1800;
        if ($watcher['model_name'] == 'ANY') $watcher['model_name'] = null;
        if ($watcher['fuel_type'] == 'ANY') $watcher['fuel_type'] = null;
        if (empty($watcher['fuel_level'])) $watcher['fuel_level'] = 100;
        if (empty($watcher['distance'])) $watcher['distance'] = 15;
        if (empty($watcher['address'])){
            $request->session()->flash('info', ['Please enter address!']);
            return redirect('/home');
        }

        Watcher::create($watcher);
        $request->session()->flash('info', ['Watcher creation successful!']);

        return redirect('/home');
    }

    public function toggle($id, \Illuminate\Http\Request $request)
    {
        $watcher = Watcher::find($id);

        $watcher->on = !$watcher->on;

        $request->session()->flash('info', ["Watcher turned ". ($watcher->on ? 'on' : 'off') ."!"]);

        $watcher->save();

        return redirect('/home');
    }

    public function off($id, \Illuminate\Http\Request $request)
    {
        $watcher = Watcher::find($id);

        $watcher->on = false;

        $request->session()->flash('info', ["Watcher turned ". ($watcher->on ? 'on' : 'off') ."!"]);

        $watcher->save();

        return redirect('/home');
    }

    public function delete($id)
    {
        DB::table('watchers')
            ->where('user_id', Auth::user()->id)
            ->where('id', $id)
            ->delete();

        return redirect('/home');
    }

    public function check($id)
    {
        $watcher = Watcher::find($id);
        $checker = new Checker();
        $cars = $checker->getCars($watcher);
        return view('check', compact('cars', 'watcher'));
    }
}