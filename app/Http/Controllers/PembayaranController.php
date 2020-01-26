<?php 

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PembayaranController extends Controller
{
    /**
    * Display a listing of the resource.
    * 
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        if (Gate::denies('read-pembayarans')) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }
        if (Auth::user()->role === 'admin') {
            // kalau admin, akan query semua Pembayaran record
            $pembayarans = Pembayaran::OrderBy("id", "DESC")->paginate(2)->toArray();
        } else {
            // kalau bukan admin, hanya akan menampilkan query berdasarkan user login
            $pembayarans = Pembayaran::Where(['user_id' => Auth::user()->id])->OrderBy("id", "DESC")->paginate(2)->toArray();
        }
        // authorization end
        
        $response = [
            "total_count" => $pembayarans["total"],
            "limit" => $pembayarans["per_page"],
            "pagination" => [
                "next_page" => $pembayarans["next_page_url"],
                "current_page" => $pembayarans["current_page"]
            ],
            "data" => $pembayarans["data"],
        ];
        /*$outPut = [
            "message" => "pembayaran",
            "results" => $pembayarans
        ];*/
        return response()->json($pembayarans, 200);
       /*$acceptHeader = $request->header('Accept');

        // Validasi: hanya application json atau xml yang valid
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $pembayarans = Pembayaran::OrderBy("id", "DESC")->paginate(10);

            if ($acceptHeader === 'application/json'){
                return response()->json($pembayarans->items('data'),200);
            } else {
                // create xml $pembayarans element
                $xml = new \SimpleXMLElement('<pembayarans/>');
                foreach ($pembayarans->items('data') as $item) {
                    // create xml $pembayarans element
                    $xmlItem = $xml->addChild('pembayaran');
                    //mengubah setiap field $pembayaran menjadi bentuk xml
                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('pembayaran_id', $item->pembayaran_id);
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
            'pemesanan_id' => 'required|exist:pemesanan,id',
            'totalbayar' => 'required|min:3'
        ];
        $validator = \Validator::make($input, $validationRules);
        if ($validator->fails()){
            return response()->json($validator->errors(), 400);
        }*/
        $pembayarans = Pembayaran::create($input);

        if (Gate::denies('store-pembayarans', $pembayarans)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }


        return response()->json($pembayarans, 200);
        /*
        $acceptHeader = $request->header('Accept');
        
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            
            $contentTypeHeader = $request->header('Content-Type');

            if ($contentTypeHeader === 'application/json' || $contentTypeHeader === 'application/xml') {
                $input = $request->all();
                $pembayarans = Pembayaran::create($input);

                return response()->json($pembayarans, 200);
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
        $pembayarans = Pembayaran::find($id);
        if (Gate::denies('show-pembayarans', $pembayarans)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }
        if(!$pembayarans) {
            abort(404);
        }
        return response()->json($pembayarans, 200);
        /*
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $pembayarans = Pembayaran::find($id);

            if(!$pembayarans) {
                abort(404);
            }
            return response()->json($pembayarans, 200);
        } else{
            return response('Not Acceptable!', 406);
        }*/
    }
    /**
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id_gedung
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $pembayarans = Pembayaran::find($id);

        if(!$pembayarans) {
            abort(404);
        }
        //authorization

        if (Gate::denies('update-pembayarans', $pembayarans)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }
        // validation
        /*$validationRules = [
            'pemesanan_id' => 'required|exist:pemesanan,id',
            'totalbayar' => 'required|min:3'
        ];
        $validator = \Validator::make($input, $validationRules);
        if ($validator->fails()){
            return response()->json($validator->errors(), 400);
        }*/
        //validation end
        $pembayarans->fill($input);
        $pembayarans->save();

        return response()->json($pembayarans, 200);
        /*
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $contentTypeHeader = $request->header('Content-Type');

            if ($contentTypeHeader === 'application/json' || $contentTypeHeader === 'application/xml'){
                $input = $request->all();

                $pembayarans = Pembayaran::find($id);

                if(!$pembayarans) {
                    abort(404);
                }
                $pembayarans->fill($input);
                $pembayarans->save();

                return response()->json($pembayarans, 200); 
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
     * @param int $id_gedung
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $pembayarans = Pembayaran::find($id);

        if(!$pembayarans){
            abort(404);
        }
        // authorization
        if (Gate::denies('delete-pembayarans', $pembayarans)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }
        // end authorization
        $pembayarans->delete();
        $message = ['message' => 'deleted successfully', 'pembayaran_id' => $id];
        /*
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $pembayarans = Pembayaran::find($id);

            if(!$pembayarans){
                abort(404);
            }
            $pembayarans->delete();
            $message = ['message' => 'deleted successfully', 'pembayaran_id' => $id];
        } else {
            return response('Not Acceptable!', 406);
        }*/
    }
}