<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\DocumentDetailResource;
use App\Models\DocumentDetail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\sendEmail;
use App\Models\OTP;
use Illuminate\Support\Facades\Mail;
use App\Models\SharedDocument;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->query('userId');
        $search = $request->query('search');
    
        $postsQuery = Document::where('user_id', $userId)->orderBy('updated_at', 'DESC');
        $documentSharedQuery = SharedDocument::where('user_id', $userId)->orderBy('updated_at', 'DESC');
    
        if ($search !== null && $search !== '') {
            $searchTerm = '%' . strtolower($search) . '%';
            $postsQuery->whereRaw('LOWER(title) LIKE ?', [$searchTerm]);
            $documentSharedQuery->whereHas('documentData', function ($query) use ($searchTerm) {
                $query->whereRaw('LOWER(title) LIKE ?', [$searchTerm]);
            });
        }
        
        $posts = $postsQuery->get();
        $documentShared = $documentSharedQuery->with('documentData')->get();
        
        return response()->json([
            'document' => DocumentResource::collection($posts),
            'documentShared' => DocumentResource::collection($documentShared->pluck('documentData')), 
        ], 200);
    }

    public function show($id){
        $post = DocumentDetail::with('docTitle')->where('document_id', $id)->first();

        if($post == null) return null;
        return new DocumentDetailResource($post);
    }

    public function checkPassword(Request $request){
        $validated = $request->validate([
            'document_id' => 'required',
            'password' => 'required',
        ]);

        $document = Document::where('document_id', $request['document_id'])->first();

        if($document->password == $request['password']){
            return response()->json([
                'message' => 'Password is correct',
            ], 200);
        }else{
            return response()->json([
                'message' => 'Password is incorrect',
            ], 401);
        }
    }

    public function store(Request $request){
        $validated = $request->validate([
            'document_id' => 'required',
            'title' => 'required|max:255',
            'content' => 'required',
            'password' => 'required',
        ]);

        $documentDetail = new Document();
        $documentDetail->document_id = $request['document_id'];
        $documentDetail->user_id= $request['user_id'];
        $documentDetail->title = $request['title'];
        $documentDetail->password = $request['password'];
        $documentDetail->save();

        $document = new DocumentDetail();
        $document->document_detail_id = Str::uuid(); 
        $document->fill($request->all());
        $document->save();
        
        return response()->json([
            'message' => 'Document and Document Detail inserted successfully',
            'document' => new DocumentResource($documentDetail),
            'documentDetail' => new DocumentDetailResource($document),
        ], 201);
    }

    public function sendOtp(Request $request){
        $documentData = Document::Where('document_id', $request['document_id'])->first();

        if($documentData == null) return response()->json("Document tidak ditemukan", 404);
        
        if($request['type'] == 'forgotpw'){
            if($documentData->user_id != $request['user_id']) return response()->json("User bukan owner dari dokumen", 404);
        }

        $user = User::where('google_id', $request['user_id'])->first();

        do{
            $otp = substr(str_shuffle(str_repeat('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', 6)), 0, 6);
            $otpExist = OTP::where('otp', $otp)->where('expired_at', '>', now())->first();
        }while($otpExist != null);

        Mail::to($user->email)->send(new sendEmail($user->name, $otp, $documentData->title, $request['type']));

        $otp_id = Str::uuid();
        OTP::create([
            'otp_id' => $otp_id, 
            'document_id' => $request['document_id'],
            'user_id' => $request['user_id'],
            'otp' => $otp,
            'expired_at' => now()->addMinutes(10),
        ]);

        return response()->json("OTP sent and saved successfully", 200);
    }

    public function verifyOtp(Request $request){
        $validated = $request->validate([
            'otp' => 'required',
            'document_id' => 'required',
            'user_id' => 'required',
        ]);

        $otp = OTP::where('document_id', $request['document_id'])
        ->where('otp', $request['otp'])
        ->where('user_id', $request['user_id'])->first();

        if($otp == null) return response()->json("OTP salah", 401);

        $otp->delete();

        return response()->json("OTP berhasil diverifikasi", 200);
    }

    public function resetPassword(Request $request){
        $validated = $request->validate([
            'document_id' => 'required',
            'password' => 'required',
            'content' => 'required',
        ]);

        $document = Document::where('document_id', $request['document_id'])->first();
        $document->password = $request['password'];
        $document->save();

        $documentDetail = DocumentDetail::where('document_id', $request['document_id'])->first();
        $documentDetail->content = $request['content'];
        $documentDetail->save();

        return response()->json("Password berhasil di reset", 200);
    }


    public function update(Request $request, $document_id){
        if($document_id == null) return response()->json("Document tidak ditemukan", 404);
        
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $document = Document::where('document_id', $document_id)->first();
        $document->title = $request['title'];
        $document->save();

        $documentDetail = DocumentDetail::where('document_id', $document_id)->first();
        $documentDetail->content = $request['content'];
        $documentDetail->save();

        if($document->user_id != $request['user_id']){
            $sharedDocument = SharedDocument::where('document_id', $document_id)->where('user_id', $request['user_id'])->first();

            if($sharedDocument == null){
                SharedDocument::create([
                    'document_shared_id' => Str::uuid(),
                    'document_id' => $document_id,
                    'user_id' => $request['user_id'],
                ]);
            }else{
                $sharedDocument->updated_at = now();
                $sharedDocument->save();
            }
        };

        return response()->json("Document berhasil di update");
    }
}
