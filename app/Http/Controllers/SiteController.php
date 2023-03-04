<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\Student;
use Termwind\Components\Dd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SiteController extends Controller
{
    private $kumpulFile;

    public function __construct()
    {
        $tugas = Task::where('type', 'Tugas')->orderBy('week', 'desc')->first();
        if ($tugas) {
            $this->kumpulFile = !($tugas->is_closed);
        }
    }

    public function index()
    {
        $task = Task::where('type', 'Absen')->get();

        $kumpulFile = $this->kumpulFile;

        return view('index', compact('task', 'kumpulFile'));
    }

    public function post(Request $request)
    {
        $request->validate([
            'student_id' => 'required|numeric|min_digits:9|max_digits:9',
            'name' => 'required',
            'submission' => 'file|mimes:zip,rar',
        ], [
            'student_id.required' => 'NRP harus diisi',
            'student_id.numeric' => 'NRP harus berupa angka',
            'student_id.min_digits' => 'NRP mu ga valid',
            'student_id.max_digits' => 'NRP mu ga valid',
            'name.required' => 'Nama harus diisi',
            'submission.file' => 'File harus berupa file',
            'submission.mimes' => 'File harus berupa file zip atau rar',
        ]);

        $student = Student::with('tasks')->where('nrp', $request->student_id)->first();

        if (!$student) {
            try {
                $student = Student::create([
                    'nrp' => $request->student_id,
                    'nama' => $request->name,
                ]);
            } catch (\Throwable $th) {
                //throw $th;
            }
        } else {
            $isClosed = Task::where('type', 'Absen')->where('week', $request->present)->first()->is_closed;
            if ($isClosed) {
                return redirect()->back()->with('error', 'Absen sudah ditutup');
            }

            $student->tasks()->attach($request->present);
        }

        if ($request->hasFile('submission') && $this->kumpulFile) {
            $lastTask = Task::where('type', 'Tugas')->orderBy('week', 'desc')->first();
            // dd($lastTask);
            $file = $request->file('submission');
            $fileName = 'T' . $lastTask->week . '_' . $student->nrp . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/submissions/' . 'Tugas ' . $lastTask->week, $fileName);

            $student->tasks()->attach($lastTask->id);
        }

        return redirect()->back()->with('success', 'Naiss! :)');
    }

    public function requestPage()
    {
        return view('request');
    }

    public function requestToken(Request $request)
    {
        $closed = $request->has('closed') ? true : false;

        $request->validate([
            'type' => 'required|in:Absen,Tugas',
            'period' => 'required|numeric|min:1|max:18',
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, '$2y$10$dPp5oi8FTgeCWnsYwXBkIuxQ5gtBBi/I6R8aWcO.MTPm6.NLi0AWG')) {
            return redirect()->back()->with('errorpass', 'Password salah');
        }

        $task = Task::where('type', $request->type)->where('week', $request->period)->first();

        if ($task) {
            $task->is_closed = $closed;
            $task->save();
            return redirect()->back()->with('errordup', 'Absen sudah diupdate!');
        }

        try {
            Task::create([
                'type' => $request->type,
                'week' => $request->period,
                'is_closed' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }

        return redirect()->back()->with('success', 'Waktunya absen! :)');
    }

    public function fetchAbsen(Request $request)
    {
        $nrp = $request->nrp;

        $student = Student::where('nrp', $nrp)->first();

        if (!$student) {
            return response([
                'status' => 'error',
                'message' => 'NRP tidak ditemukan',
            ], 404);
        }

        $absen = $student->tasks()->where('type', 'Absen')->get();

        return response([
            'status' => 'success',
            'nama' => $student->nama,
            'absen' => $absen,
            'semua_absen' => Task::where('type', 'Absen')->get(),
        ], 200);
    }

    public function fetchClose(Request $request)
    {
        $absen = Task::where('type', $request->type)->where('week', $request->period)->first();

        return response($absen, 200);
    }
}
