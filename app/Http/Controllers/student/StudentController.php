<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class StudentController extends Controller
{
    private function fetchData(string $table, $id = null, array $extraWhere = [])
    {
        $query = DB::table($table);

        if ($id !== null) {
            return $query->where('id', $id)->first();
        }

        if (!empty($extraWhere)) {
            $query->where($extraWhere);
        }

        return $query->get()->toArray();
    }

    public function getClasses($id = null)
    {
        return $this->fetchData('classes', $id);
    }

    public function getAcademicSession($id = null)
    {
        $where = ($id === null) ? ['is_active' => 1] : [];
        return $this->fetchData('academic_sessions', $id, $where);
    }

    public function getKitItems($id = null)
    {
        return $this->fetchData('kit_items', $id);
    }

    public function getReligions($id = null)
    {
        return $this->fetchData('religions', $id, ['is_active' => 1]);
    }

    public function getClassFee($classId, $sessionId)
    {
        return DB::table('class_fees')
            ->where('class_id', $classId)
            ->where('academic_session_id', $sessionId)
            ->first();
    }

    public function getClassFeesAPI($classId, $sessionId)
    {
        $fee = $this->getClassFee($classId, $sessionId);

        return response()->json([
            'status'  => 200,
            'message' => 'Success',
            'data'    => $fee
        ]);
    }

    public function getCasteCategories($id = null)
    {
        return $this->fetchData('caste_categories', $id);
    }

    public function getStudentFromCreatingData()
    {
        try {
            return response()->json([
                'status'  => true,
                'message' => 'Success',
                'data'    => [
                    'classes'          => $this->getClasses(),
                    'academic_session' => $this->getAcademicSession(),
                    'kit_items'        => $this->getKitItems(),
                    'religions'        => $this->getReligions(),
                    'caste_categories' => $this->getCasteCategories(),
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Internal Server Error',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function saveStudent(Request $request)
    {
        try {
            $data = $request->json()->all();

            $classId   = $data['class_id'];
            $sessionId = $data['academic_session_id'];

            $studentId = DB::table('students')->insertGetId($data);

            $fee = $this->getClassFee($classId, $sessionId);

            if ($fee) {
                DB::table('student_fees')->insert([
                    'student_id'   => $studentId,
                    'class_fee_id' => $fee->id,
                    'amount'       => $fee->fee_amount,
                    'status'       => 'pending'
                ]);
            }

            return response()->json([
                'status'     => 201,
                'message'    => 'Student created successfully',
                'student_id' => $studentId
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Error saving student',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
