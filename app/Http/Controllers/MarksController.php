<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentMarks;
use App\Models\Term;
use App\Models\Student;
use App\Models\TermSubject;
use App\Models\Subject;

class MarksController extends Controller
{
    public function list()
    {
        $subjects = Subject::orderBy('id', 'desc')->get();

        $terms = Term::all();
        $data = [];
        foreach($terms as $term) {

            $studentMarks = StudentMarks::leftjoin('students', 'students.id', '=', 'student_marks.student_id')
                                        ->leftjoin('terms', 'terms.id', '=', 'student_marks.term_id')
                                        ->select('student_marks.id', \DB::raw("DATE_FORMAT(student_marks.created_at, '%M %d, %Y %r') as created_on"), 'students.name as student', 'terms.name as term', 'students.id as student_id', 'student_marks.term_id')
                                        ->where('student_marks.term_id', '=', $term->id)
                                        ->get();

            foreach($studentMarks as $studentMark) {

                $studentSubjects = StudentMarks::where(['student_id' => $studentMark->student_id, 'term_id' => $term->id])
                                    // ->select('subject_id')
                                    ->orderBy('subject_id', 'desc')
                                    ->get()
                                    ->pluck('subject_id')->toArray();

                $subjectMarks = [];

                foreach($subjects as $subject) {

                    if(in_array($subject->id, $studentSubjects)) {
                        $subjectMarks[] = StudentMarks::where(['student_id' => $studentMark->student_id, 'term_id' => $term->id, 'subject_id' => $subject->id])->pluck('mark')[0];
                    } else {
                        $subjectMarks[] = 'NA';
                    }
                }

                $studentMark['marks'] = $subjectMarks;
                $data[] = $studentMark;
            }  
        }
        
        return view('mark.student-marks', compact('subjects', 'data'));
    }

    public function add(Request $request)
    {
        $terms = Term::all();

        $students = Student::all();

        return view('mark.student-mark-add', compact('terms', 'students'));
    }

    public function termSubjects(Request $request)
    {
        $termSubjects = TermSubject::join('subjects', 'subjects.id', '=', 'term_subjects.subject_id')
                                ->select('subjects.id as subject_id', 'subjects.name as subject_name', 'term_subjects.term_id')
                                ->where('term_subjects.term_id', '=', $request->termid)
                                ->get();

        if(count($termSubjects) > 0) {

            return response()->json([
                'status' => true,
                'data' => $termSubjects,
                'message' => 'success'
            ], 200);

        } else {

            return response()->json([
                'status' => false,
                'message' => 'Data not found'
            ], 200);
        }
    }

    public function saveMarks(Request $request)
    {
        try{
            \DB::beginTransaction();

            $validatedData = \Validator::make($request->all(), [
                'student' => 'required',
                'term' => 'required',
                "subjectmark"  => "required|array",
                "subjectmark.*" => "required|numeric",
            ]);

            if ($validatedData->fails()) {
                return response()->json(['status' => false, 'message' => $validatedData->messages()->first()], 200);
            }

            $studentId = $request->student;
            $termId = $request->term;
            $count = 0;

            foreach($request->subjectmark as $key => $value) {

                $studentMark = new StudentMarks;
                $studentMark->student_id = $studentId;
                $studentMark->term_id = $termId;
                $studentMark->subject_id = $key;
                $studentMark->mark = $value;
                $studentMark->save();

                $count++;

            }

            if(count($request->subjectmark) == $count) {

                \DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Marks saved'
                ], 200);

            }

            \DB::rollback();

            return response()->json([
                'status' => false,
                'message' => 'Some error occured'
            ], 200);

        } catch(Exception $e) {
            \DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()->first()
            ], 200);
        }
    }

    // public function fetchMarks(Request $request)
    // {

    //     $studentMarks = StudentMarks::leftjoin('students', 'students.id', '=', 'student_marks.student_id')
    //                                 ->leftjoin('terms', 'terms.id', '=', 'student_marks.term_id')
    //                                 // ->leftjoin('subjects', 'subjects.id', '=', 'student_marks.subject_id')
    //                                 ->select('student_marks.id',\DB::raw("DATE_FORMAT(student_marks.created_at, '%M %d, %Y %r') as created_on"), 'terms.name as term', /*'subjects.name as subject',*/ 'students.name as student')
    //                                 ->get();

    //     $data = array(
    //         'draw' => $request->draw,
    //         'recordsTotal' => count($studentMarks),
    //         'recordsFiltered' => count($studentMarks),
    //         'data' => $studentMarks,
    //     );
        
    //     return response()->json($data);

    // }

    public function editMarks($stdid, $termid)
    {
        $terms = Term::all();

        $students = Student::all();

        $markDetails = StudentMarks::leftjoin('subjects', 'subjects.id', '=', 'student_marks.subject_id')->where(['student_id' => $stdid, 'term_id' => $termid])->get();
        
        return view('mark.student-mark-edit', compact('terms', 'students', 'markDetails'));
    }

    public function marksUpdate(Request $request)
    {
        try{
            \DB::beginTransaction();

            $validatedData = \Validator::make($request->all(), [
                'student' => 'required',
                'term' => 'required',
                "subjectmark"  => "required|array",
                "subjectmark.*" => "required|numeric",
            ]);

            if ($validatedData->fails()) {
                return response()->json(['status' => false, 'message' => $validatedData->messages()->first()], 200);
            }

            $studentId = $request->student;
            $termId = $request->term;
            $count = 0;

            foreach($request->subjectmark as $key => $value) {

                $studentMark = StudentMarks::where(['student_id' => $studentId, 'term_id' => $termId, 'subject_id' => $key])->first();
                $studentMark->mark = $value;
                $studentMark->save();

                $count++;

            }

            if(count($request->subjectmark) == $count) {

                \DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Marks Updated'
                ], 200);

            }

            \DB::rollback();

            return response()->json([
                'status' => false,
                'message' => 'Some error occured'
            ], 200);

        } catch(Exception $e) {
            \DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()->first()
            ], 200);
        }
    }

    public function marksDelete(Request $request)
    {
        StudentMarks::where(['student_id' => $request->stddId, 'term_id' => $request->termId])->delete();

        return response()->json([
            'status' => true,
            'message' => 'Marks Delete'
        ], 200);
    }
}
