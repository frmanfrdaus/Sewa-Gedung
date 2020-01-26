<?php 

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PemesananController extends Controller
{
    /**
    * Display a listing of the resource.
    * 
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        // authorization
        // chec if current user is authorized to do this action

        if (Gate::denies('read-pemesanans')) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }
        if (Auth::user()->role === 'admin') {
            // kalau admin, akan query semua pemesanan record
            $pemesanans = Pemesanan::OrderBy("id", "DESC")->paginate(2)->toArray();
        } else {
            // kalau bukan admin, hanya akan menampilkan query berdasarkan user login
            $pemesanans = Pemesanan::Where(['user_id' => Auth::user()->id])->OrderBy("id", "DESC")->paginate(2)->toArray();
        }
        // authorization end

        $response = [
            "total_count" => $pemesanans["total"],
            "limit" => $pemesanans["per_page"],
            "pagination" => [
                "next_page" => $pemesanans["next_page_url"],
                "current_page" => $pemesanans["current_page"]
            ],
            "data" => $pemesanans["data"],
        ];
        return response()->json($pemesanans, 200);
         /*$outPut = [
            "message" => "pemesanan",
            "results" => $pemesanans
        ];*/
       /* $acceptHeader = $request->header('Accept');

        // Validasi: hanya application json atau xml yang valid
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $pemesanans = Pemesanan::OrderBy("id", "DESC")->paginate(10);

            if ($acceptHeader === 'application/json'){
                return response()->json($pemesanans->items('data'),200);
            } else {
                // create xml $pemesanans element
                $xml = new \SimpleXMLElement('<pemesanans/>');
                foreach ($pemesanans->items('data') as $item) {
                    // create xml $pemesanans element
                    $xmlItem = $xml->addChild('pemesanan');
                    //mengubah setiap field $pemesanan menjadi bentuk xml
                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('pemesanan_id', $item->pemesanan_id);
                    $xmlItem->addChild('tanggalpesan', $item->tanggalpesan);
                    $xmlItem->addChild('totalbayar', $item->totalbayar);
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
        /*$validationRules = [
            'gedung_id' => 'required|exist:gedung,id',
            'user_id' => 'required|exist:user,id',
            'tanggalpesan' => 'required|min:3',
            'totalbayar' => 'required|min:3'
        ];
        $validator = \Validator::make($input, $validationRules);
        if ($validator->fails()){
            return response()->json($validator->errors(), 400);
        }*/
        $pemesanans = Pemesanan::create($input);

        if (Gate::denies('store-pemesanans', $pemesanans)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }

        return response()->json($pemesanans, 200);
        /*$acceptHeader = $request->header('Accept');
        
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            
            $contentTypeHeader = $request->header('Content-Type');

            if ($contentTypeHeader === 'application/json' || $contentTypeHeader === 'application/xml') {
                $input = $request->all();
                $pemesanans = Pemesanan::create($input);

                return response()->json($pemesanans, 200);
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
        $pemesanans = Pemesanan::find($id);

        if (Gate::denies('show-pemesanans', $pemesanans)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }

        if(!$pemesanans) {
            abort(404);
        }
        return response()->json($pemesanans, 200);
        /*$acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $pemesanans = Pemesanan::find($id);

            if(!$pemesanans) {
                abort(404);
            }
            return response()->json($pemesanans, 200);
        } else{
            return response('Not Acceptable!', 406);
        }*/
    }
    /**
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id_pemesanan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $pemesanans = Pemesanan::find($id);
        if(!$pemesanans) {
            abort(404);
        }
        //authorization
        if (Gate::denies('update-pemesanans', $pemesanans)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }
        $pemesanans->fill($input);
        $pemesanans->save();

        return response()->json($pemesanans, 200);
        // validation
        /*$validationRules = [
            'gedung_id' => 'required|exist:gedung,id',
            'user_id' => 'required|exist:user,id',
            'tanggalpesan' => 'required|min:3',
            'totalbayar' => 'required|min:3'
        ];
        $validator = \Validator::make($input, $validationRules);
        if ($validator->fails()){
            return response()->json($validator->errors(), 400);
        }*/
        //validation end
        /*$acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $contentTypeHeader = $request->header('Content-Type');

            if ($contentTypeHeader === 'application/json' || $contentTypeHeader === 'application/xml'){
                $input = $request->all();

                $pemesanans = Pemesanan::find($id);

                if(!$pemesanans) {
                    abort(404);
                }
                $pemesanans->fill($input);
                $pemesanans->save();

                return response()->json($pemesanans, 200); 
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
     * @param int $id_pemesanan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $pemesanans = Pemesanan::find($id);

        if(!$pemesanans){
            abort(404);
        }
        // authorization
        if (Gate::denies('delete-pemesanans', $pemesanans)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }
        // end authorization
        $pemesanans->delete();
        $message = ['message' => 'deleted successfully', 'pemesanan_id' => $id];
        return response()->json($message, 200);
        /*$acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $pemesanans = Pemesanan::find($id);

            if(!$pemesanans){
                abort(404);
            }
            $pemesanans->delete();
            $message = ['message' => 'deleted successfully', 'pemesanan_id' => $id];
        } else {
            return response('Not Acceptable!', 406);
        }*/
    }
}