<?php

namespace App\Http\Controllers;
use App\Models\Productimg;
use Illuminate\Routing\Controller;


class Productimgs extends Controller
{
    public function viewimg()
    {
        $imgs = Productimg::all();
        return view('users.Uproducts',compact('imgs'));
    }
}
