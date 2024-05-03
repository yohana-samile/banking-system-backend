<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\Region;
    use App\Models\District;
    use App\Models\Ward;

    class LocationController extends Controller {
        public function index(){
            $regions = Region::all();
            return response()->json($regions);
        }
        public function districts(){
            $districts = District::all();
            return response()->json($districts);
        }
        public function wards(){
            $wards = Ward::all();
            return response()->json($wards);
        }
        public function regions(){
            $regions = Ward::all();
            return response()->json($regions);
        }
    }
