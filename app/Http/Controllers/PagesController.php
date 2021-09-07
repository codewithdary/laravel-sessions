<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PagesController extends Controller
{
    public function index()
    {
        // Add a new name inside your 'name' session
        //Session::put('name', 'John');

        //Add a value to an existing array with the push method
        //Session::put('name', ['John']);
        //Session:push('name', 'Michael');

        //Has method checks whether there's a value set at the provided key.
        //if(Session::has('name')) {
        //    echo 'Name does exist';
        //}

        //Exists method checks whether a value exists
        //Has method checks whether there's a value set at the provided key.
        //if(Session::exists('name')) {
        //    echo 'Name does exist';
        //}

        //Get all values from a session
        ///Session::all();

        //Forget removes all values from a given key and flush removes it all.
        //Session::forget('name');
        //Session::flush();

        //Regenerate _token from your session
        ///Session::regenerate();

        dd(Session::all());

        return view('index');
    }

    public function about()
    {
        return view('about');
    }
}
