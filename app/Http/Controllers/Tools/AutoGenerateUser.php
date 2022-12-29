<?php

namespace App\Http\Controllers\Tools;

use App\Exports\UsersTmpExport;
use App\Http\Controllers\Controller;
use App\Jobs\BigInsert;
use App\Jobs\GenerateUser;
use App\Models\Guru;
use App\Models\User;
use App\Models\UserTmp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

use function Psy\debug;

class AutoGenerateUser extends Controller
{
    private function random_username($string) {
        $pattern = " ";
        $firstPart = strstr(strtolower($string), $pattern, true);
        $secondPart = substr(strstr(strtolower($string), $pattern, false), 0,3);
        $nrRand = rand(0, 100);
        
        $username = trim($firstPart).trim($secondPart).trim($nrRand);
        return $username;
    }

    public function view(Request $req)
    {
        return view('tools.generateUser');
    }


    public function generate(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'password' => "required|min:8|regex:/(^[a-zA-Z]+[a-zA-Z0-9\\-]*$)/u"
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => 'Terjadi galat, mohon cek lagi',
                'messages' => $validator->errors(),
                'data' => []
            ]);
        }

        $_guru = Guru::all();
        $_jobs = [];
        $_users = [];       
        // $_debug = []; 
        foreach ($_guru as $value) {
            // $_debug[] = [
            //     'name' => $value->nama,
            //     'ada' => Guru::isHaveUser($value->id)
            // ];
            if(!Guru::isHaveUser($value->id)){
                $username = $this->random_username($value->nama);
                $password = $req->password;
                $_users[] = [
                    'name' => $value->nama,
                    'username' => $username,
                    'password' => $password
                ];
                $_jobs[] = new GenerateUser($username, $value->nama, $value->id, $password);
            }
        }


        if (!count($_jobs) < 1) {
            $batch = Bus::batch($_jobs)->dispatch();    
            
            
            for ($i=0; $i < count($_users); $i++) { 
                $_users[$i]['batch_id'] = $batch->id;                
            }

            $bigInsert = Bus::batch([new BigInsert($_users, UserTmp::class)])->dispatch();
            

            return response([
                'status' => true,
                'message' => "Batch jobs sudah dibuat, mohon tunggu sebentar",
                'data' => [
                    'id_batch' => $batch->id,
                    'big_insert_id' => $bigInsert->id,
                    'user_count' => count($_users),
                ]
            ]);
        }else{
            return response([
                'status' => false,
                'message' => "Semua guru sudah memiliki akun",
                'data' => []
            ]);
        }

    }

    public function infoBatch(Request $req, $id_batch)
    {
        try {            
            $batch = Bus::findBatch($id_batch);
            return response([
                'status' => true,
                'message' => "Berhasil mendapatkan info batch",
                'data' => [
                    'id' => $batch->id,
                    'total_jobs' => $batch->totalJobs,
                    'pending_jobs' => $batch->pendingJobs,
                    'failed_jobs' => $batch->failedJobIds,
                    'proccessed_jobs' => $batch->processedJobs(),
                    'progress' => $batch->progress(),
                    'finished' => $batch->finished()
                ]
            ]);
        } catch (\Throwable $th) {
            return response([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => []
            ]);
        }
    }

    public function getAll(Request $req)
    {
        // htmlspecialchars(json_encode($arr), ENT_QUOTES, 'UTF-8')
        $allUser = Guru::all();
        return DataTables::of($allUser)->addIndexColumn()->addColumn('aksi', function($row){
            $btn = '<div class="d-flex justify-content-center align-items-center">
            <button onclick="editUser(this)" data-json="'.htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8').'" type="button" class="btn mr-1 btn-warning btn-sm"><i class="bi bi-pencil"></i></button>
            <button onclick="deleteUser('.$row->id.')" type="button" class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
        </div>';
            return $btn;
        })->addColumn('nama', function($row){
            $img = '<div class="d-flex justify-content-start gap-3 align-items-center">
            <img src="'.url('image/guru/'.$row->profile).'" class="img-fluid rounded-circle mr-3" style="object-fit: cover;width:64px;height:64px;" alt=""> 
            <span class="">'.$row->nama.'</span>
            </div>';
            return $img;
        })->addColumn('nama_raw', function($row){
            return $row->nama;
        })->addColumn('isHaveUser', function($row){
            if(Guru::isHaveUser($row->id)){
                return '<div class="d-flex justify-content-center align-items-center">
                <h1 style="font-size: 64px;"><i class="bi bi-dot text-success"></i></h1>
                </div>';
            }else{
                return '<div class="d-flex justify-content-center align-items-center">
                    <h1 style="font-size: 64px;"><i class="bi bi-dot text-danger"></i></h1>
                </div>';
            }
        })->rawColumns(['aksi','nama', 'isHaveUser'])->make(true);
    }

    public function export(Request $req)
    {
        $userExport = new UsersTmpExport($req->id_batch);
        return $userExport->download('users-scheduler-pro-'.$req->id_batch.'.xlsx');
    }

}
