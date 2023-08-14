<?php

namespace App\Http\Controllers;
use App\Models\Trip;
use App\Models\Mark;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function index()
    {
        $markers = Mark::all();
        $markerData = [];
        foreach ($markers as $marker) {
            $markerData[] = [
                'id' =>$marker->id,
                'desc' =>$marker->desc,
                'name' => $marker->name,
                'address' => $marker->address,
                'latitude' => $marker->latitude,
                'longitude' => $marker->longitude,
            ];
        }

        return view('trip_creator', compact('markerData'));
    }

    public function store(Request $request)
    {

/*      $validatedData = $request->validate([
                    'name' => 'required|min:3',
                    'desc' => 'required',
                    'address' => 'required',
                    'latitude' => 'required',
                    'longitude' => 'required',
                ]);

                try {
                    $mark = new Mark();
                    $mark->name = $request->name;
                    $mark->desc = $request->desc;
                    $mark->address = $request->address;
                    $mark->latitude = $request->latitude;
                    $mark->longitude = $request->longitude;
                    $mark->save();

                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Wystąpił błąd podczas zapisywania danych.' . $e->getMessage());
                }
        return redirect()->back()/*->with('success', 'Dane zapisane pomyślnie.')*/;
        $mark = new Mark();
        $mark->name = $request->name;
        $mark->desc = $request->desc;
        $mark->address = $request->address;
        $mark->latitude = $request->latitude;
        $mark->longitude = $request->longitude;
        $mark->save();
    }
}
