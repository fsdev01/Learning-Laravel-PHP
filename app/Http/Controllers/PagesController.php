<?php

// Unique Namespace 
namespace App\Http\Controllers;

// import Request Library to handle requests
use Illuminate\Http\Request;

// Extends Controller Base Class (Core Controller)
class PagesController extends Controller
{
    // Index Method return pages/index view
    public function index() {
        $title = "Welcome to Laravel!!";
        
        // Load a view
        return view("pages.index",compact('title'));
        //return "Index";
    }

    // About Method return pages/about view
    public function about() {
        $title = "About Us!";

        // pages/about
        return view("pages.about")->with('title',$title);
    }

    // Services Method return pages/services view
    public function services(){
        $data = array(
            'title' => 'Services',
            'services' => ['Web Design','Programming','SEO']
        );
        return view("pages.services")->with($data);
    }


    
}
