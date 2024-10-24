<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Location;

class LocationController extends Controller
{


    public function store(Request $request)
    {
        $request->validate([
            'location_name' => 'required'
          ]);
           Location::create($request->all());

        session()->flash('add');
        return redirect()->back();
    }


}
