<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        return view('home');
    }

    /**
     * @param mixed $id
     * 
     * @return [type]
     */
    public function show($id)
    {
        $city = City::find($id);

        return view('show', compact('city'));
    }

    
 
    /**
     * @param Request $request
     * 
     * @return [type]
     */
    public function search(Request $request)
    {
        $data = City::select('name', 'id')
                    ->where('name', 'LIKE', '%'. $request->get('query') . '%')
                    ->get();

        return response()->json($data);
    }
}
