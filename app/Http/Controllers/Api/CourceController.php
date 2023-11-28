<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourceController extends Controller
{
    public function index(){
        try {
            return DB::transaction(function () {
                // Query for bundle courses
                $bundleCourses = DB::select("
                    SELECT d.name AS department_name, c.name AS course_name
                    FROM departments d
                    LEFT JOIN department_course dc ON d.id = dc.department_id
                    LEFT JOIN courses c ON dc.course_id = c.id
                    WHERE c.course_type = '1'
                    ORDER BY d.name, c.name
                ");

                // Query for single courses
                $singleCourses = DB::select("
                    SELECT d.name AS department_name, c.name AS course_name
                    FROM departments d
                    LEFT JOIN department_course dc ON d.id = dc.department_id
                    LEFT JOIN courses c ON dc.course_id = c.id
                    WHERE c.course_type = '2'
                    ORDER BY d.name, c.name
                ");

                $response = [];

                // Organize data into the desired format for bundle courses
                foreach ($bundleCourses as $bundleCourse) {
                    $departmentName = $bundleCourse->department_name;
                    $courseName = $bundleCourse->course_name;
                    $response[$departmentName]['bundle_courses'][] = $courseName;
                }

                // Organize data into the desired format for single courses
                foreach ($singleCourses as $singleCourse) {
                    $departmentName = $singleCourse->department_name;
                    $courseName = $singleCourse->course_name;
                    $response[$departmentName]['single_courses'][] = $courseName;
                }

                return response()->json($response);
            });
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['error' => 'An error occurred while processing the request.'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
