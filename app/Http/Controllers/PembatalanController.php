<?php 

namespace App\Http\Controllers;

use App\Models\Pembatalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PembatalanController extends Controller
{
    /**
    * Display a listing of the resource.
    * 
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        if (Gate::denies('read-pembatalans')) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }
        if (Auth::user()->role === 'admin') {
            // kalau admin, akan query semua pemesanan record
            $pembatalans = Pembatalan::OrderBy("id", "DESC")->paginate(2)->toArray();
        } else {
            // kalau bukan admin, hanya akan menampilkan query berdasarkan user login
            $pembatalans = Pembatalan::Where(['user_id' => Auth::user()->id])->OrderBy("id", "DESC")->paginate(2)->toArray();
        }
        
        $response = [
            "total_count" => $pembatalans["total"],
            "limit" => $pembatalans["per_page"],
            "pagination" => [
                "next_page" => $pembatalans["next_page_url"],
                "current_page" => $pembatalans["current_page"]
            ],
            "data" => $pembatalans["data"],
        ];
        /*$outPut = [
            "message" => "pembatalan",
            "results" => $pembatalans
        ];*/
        return response()->json($pembatalans, 200);
       /*
        $acceptHeader = $request->header('Accept');

        // Validasi: hanya application json atau xml yang valid
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $pembatalans = Pembatalan::OrderBy("id", "DESC")->paginate(10);

            if ($acceptHeader === 'application/json'){
                return response()->json($pembatalans->items('data'),200);
            } else {
                // create xml $pembatalans element
                $xml = new \SimpleXMLElement('<pembatalans/>');
                foreach ($pembatalans->items('data') as $item) {
                    // create xml $pembatalans element
                    $xmlItem = $xml->addChild('pembatalan');
                    //mengubah setiap field $pembatalan menjadi bentuk xml
                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('pembatalan_id', $item->pembatalan_id);
                    $xmlItem->addChild('created_at', $item->created_at);
                    $xmlItem->addChild('updated_at', $item->updated_at);
                }
                return $xml->asXML();
            }
        } else{ 
            return response('Not Acceptable!', 406);
        }*/
    }
    /**
     * Store a newly created resource in storage.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
       /* $validationRules = [
            'pemesanan_id' => 'required|exist:gedung,id'
        ];
        $validator = \Validator::make($input, $validationRules);
        if ($validator->fails()){
            return response()->json($validator->errors(), 400);
        }*/
        $pembatalans = Pembatalan::create($input);
        if (Gate::denies('store-pembatalans', $pembatalans)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }
        return response()->json($pembatalans, 200);
        /*
        $acceptHeader = $request->header('Accept');
        
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            
            $contentTypeHeader = $request->header('Content-Type');

            if ($contentTypeHeader === 'application/json' || $contentTypeHeader === 'application/xml') {
                $input = $request->all();
                $pembatalans = Pembatalan::create($input);

                return response()->json($pembatalans, 200);
            } else {
                return response('Unsupported Media Type', 415);
            }
        } else {
            return response('Not Acceptable!', 406);
        }*/
    }
    /**
     * Display the specified resource
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $pembatalans = Pembatalan::find($id);
        if (Gate::denies('show-pembatalans', $pembatalans)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }

        if(!$pembatalans) {
            abort(404);
        }
        return response()->json($pembatalans, 200);
        /*
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $pembatalans = Pembatalan::find($id);

            if(!$pembatalans) {
                abort(404);
            }
            return response()->json($pembatalans, 200);
        } else{
            return response('Not Acceptable!', 406);
        }*/
    }
    /**
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id_$pembatalan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $pembatalans = Pembatalan::find($id);

        if(!$pembatalans) {
            abort(404);
        }
        if (Gate::denies('update-pembatalans', $pembatalans)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }
        // validation
        /*$validationRules = [
            'pemesanan_id' => 'required|exist:gedung,id'
        ];
        $validator = \Validator::make($input, $validationRules);
        if ($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        //validation end*/
        $pembatalans->fill($input);
        $pembatalans->save();

        return response()->json($pembatalans, 200);
        /*$acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $contentTypeHeader = $request->header('Content-Type');

            if ($contentTypeHeader === 'application/json' || $contentTypeHeader === 'application/xml'){
                $input = $request->all();

                $pembatalans = Pembatalan::find($id);

                if(!$pembatalans) {
                    abort(404);
                }
                $pembatalans->fill($input);
                $pembatalans->save();

                return response()->json($pembatalans, 200); 
             }else {
                return response('Unsupported Media Type', 415);
            }
        } else {
            return response('Not Acceptable!', 406);
        }*/
    }
    /**
     * Remove the specified resource from storage
     * 
     * @param int $id_$pembatalan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $pembatalans = Pembatalan::find($id);

        if(!$pembatalans){
            abort(404);
        }
        if (Gate::denies('delete-pembatalans', $pembatalans)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }
        $pembatalans->delete();
        $message = ['message' => 'deleted successfully', 'pembatalan_id' => $id];
        /*
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $pembatalans = Pembatalan::find($id);

            if(!$pembatalans){
                abort(404);
            }
            $pembatalans->delete();
            $message = ['message' => 'deleted successfully', 'pembatalan_id' => $id];
        } else {
            return response('Not Acceptable!', 406);
        }*/
    }
}