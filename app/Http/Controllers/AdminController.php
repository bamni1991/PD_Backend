<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolHoliday;
use App\Models\AcademicSession;
class AdminController extends Controller
{
    public function getSchoolHolidays(Request $request)
    {
        try {
            $year = $request->query('year');

            if (!$year) {
                return response()->json([
                    'status' => false,
                    'message' => 'Year is required'
                ], 400);
            }

            // Fetch holidays for the given year using Model
            $holidays = SchoolHoliday::whereYear('holiday_date', $year)
                ->orderBy('holiday_date', 'asc')
                ->get();

            return response()->json($holidays, 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching holidays',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function updateSchoolHoliday(Request $request, $holidayId)
    {
        try {
            $holiday = SchoolHoliday::find($holidayId);

            if (!$holiday) {
                return response()->json([
                    'status' => false,
                    'message' => 'Holiday not found'
                ], 404);
            }
            $active_academic_session_id = AcademicSession::where('is_active', 1)->first()->id;

            $request->merge(['academic_session_id' => $active_academic_session_id]);

            $request->validate([
                'holiday_name' => 'required|string|max:255',
                'holiday_date' => 'required|date',
                'holiday_type' => 'required|string',
            ]);

            $holiday->update([
                'holiday_name' => $request->holiday_name,
                'holiday_date' => $request->holiday_date,
                'holiday_type' => $request->holiday_type,
                'academic_session_id' => $request->academic_session_id,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Holiday updated successfully',
                'holiday' => $holiday
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating holiday',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function deleteSchoolHoliday($holidayId)
    {
        try {
            $holiday = SchoolHoliday::find($holidayId);

            if (!$holiday) {
                return response()->json([
                    'status' => false,
                    'message' => 'Holiday not found'
                ], 404);
            }

            $holiday->delete();

            return response()->json([
                'status' => true,
                'message' => 'Holiday deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting holiday',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    // createSchoolHoliday

    public function createSchoolHoliday(Request $request)
    {
        try {
            $active_academic_session_id = AcademicSession::where('is_active', 1)->first()->id;

            $request->merge(['academic_session_id' => $active_academic_session_id]);

            $request->validate([
                'holiday_name' => 'required|string|max:255',
                'holiday_date' => 'required|date',
                'holiday_type' => 'required|string',
            ]);

            $holiday = SchoolHoliday::create([
                'holiday_name' => $request->holiday_name,
                'holiday_date' => $request->holiday_date,
                'holiday_type' => $request->holiday_type,
                'academic_session_id' => $request->academic_session_id,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Holiday created successfully',
                'holiday' => $holiday
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error creating holiday',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
