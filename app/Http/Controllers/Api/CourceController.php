<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourceController extends Controller
{
    public function index(){
        try {


            return DB::transaction(function () {
                // Query for bundle and single courses
                $courses = Department::with(['courses' => function ($query) {
                    $query->orderBy('course_type')

                        ->orderBy('name');
                }])

                    ->get();

                return response()->json([
                    'courses' => $courses,
                ]);
            });
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['error' => 'An error occurred while processing the request.'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
