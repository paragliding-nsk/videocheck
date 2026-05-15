<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Video;



class MainController extends Controller
{
    
    public function welcome() {
    return view('welcome');
    }

    public function start() {
        return Video::checkNewVideo();
    }

    public function newconf() {
        return view('newconf');
    }


    public function nonewvideo() {
        return view('nonewvideo');
    }

    public function badanswer() {
        return view('badanswer');
    }

    public function otladka() {
        return view('otladka');
    }

    public function otladka2() {
        return Video::otladka();
    }

    
}
