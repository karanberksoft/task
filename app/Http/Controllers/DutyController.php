<?php

namespace App\Http\Controllers;

use App\Mail\adddutyMail;
use App\Models\duty;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\AddDutyRequest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\UpdateDutyRequest;

class DutyController extends Controller
{
    public function AddDuty(AddDutyRequest $request)
    {

        try {
            if ($request->user->status != 'admin') {
                return response()->json([
                    'statusCode' => 400,

                    'success'   => false,

                    'message'   => 'sadece admin kullanıcısı görev ekleyebilir.',

                    'data'      => null

                ]);
            }
            $user = User::where('email', $request->dutyUserEmail)->first();
            $duty = new duty;
            $duty->user_id = $user->id;
            $duty->title = $request->title;
            $duty->content = $request->content;
            $duty->start = $request->start;
            $duty->end = $request->end;
            $duty->status = 'none';
            $duty->save();
            Mail::to($user->email)
                ->send(new adddutyMail());
            return response()->json([
                'statusCode' => 200,
                'success' => true,
                'message' => 'görev ekleme başarılı',
                'data' => null
            ]);
        } catch (\Exception $e) {
            Log::warning($request, $e->getMessage());
            throw new HttpResponseException(response()->json([
                'statusCode' => 500,

                'success'   => false,

                'message'   => 'fatal error',

                'data'      => $e->getMessage()

            ]));
        }
    }

    public function UpdateDuty(UpdateDutyRequest $request, int $id)
    {

        try {
            if ($request->has('end') || $request->has('start') || $request->has('content') || $request->has('title') || $request->user->status == 'normal') {
                return response()->json([
                    'statusCode' => 400,

                    'success'   => false,

                    'message'   => 'normal kullanıcı sadece görevlerin statulerini değiştirebilir.',

                    'data'      => null

                ]);
            }
            $duty = duty::where('id', $id)->first();
            if ($request->user->status == 'normal') {
                $duty->status = $request->status;
            } else if ($request->user->status == 'admin') {
                $request->has('title') ? $duty->title = $request->title : null;
                $request->has('content') ? $duty->content = $request->content : null;
                $request->has('start') ? $duty->start = $request->start : null;
                $request->has('end') ? $duty->end = $request->end : null;
                $duty->status = $request->status;
            }
            $duty->save();
            return response()->json([
                'statusCode' => 200,
                'success' => true,
                'message' => 'görev güncelleme başarılı',
                'data' => $duty
            ]);
        } catch (\Exception $e) {
            Log::warning($request, $e->getMessage());
            throw new HttpResponseException(response()->json([
                'statusCode' => 500,

                'success'   => false,

                'message'   => 'fatal error',

                'data'      => $e->getMessage()

            ]));
        }
    }
    public function DeleteDuty(Request $request, int $id)
    {
        try {
            if ($request->user->status == 'normal') {
                return response()->json([
                    'statusCode' => 400,

                    'success'   => false,

                    'message'   => 'normal kullanıcı görev silemez.',

                    'data'      => null

                ]);
            }
            $duty = duty::find($id);
            if ($duty == null) {
                return response()->json([
                    'statusCode' => 400,

                    'success'   => false,

                    'message'   => 'görev bulunamadı',

                    'data'      => null

                ]);
            }
            $duty->delete();

            return response()->json([
                'statusCode' => 200,
                'success' => true,
                'message' => 'görev silme başarılı',
                'data' => $duty
            ]);
        } catch (\Exception $e) {
            Log::warning($request, $e->getMessage());
            throw new HttpResponseException(response()->json([
                'statusCode' => 500,

                'success'   => false,

                'message'   => 'fatal error',

                'data'      => $e->getMessage()

            ]));
        }
    }
}
