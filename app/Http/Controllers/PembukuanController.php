<?php 

namespace App\Http\Controllers;

use App\Models\Pembukuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PembukuanController extends Controller
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
        if (Gate::denies('read-pembukuans')) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }
        if (Auth::user()->role === 'admin') {
            // kalau admin, akan query semua pemesanan record
            $pembukuans = Pembukuan::OrderBy("id", "DESC")->paginate(2)->toArray();
        } else {
            // kalau bukan admin, hanya akan menampilkan query berdasarkan user login
            $pembukuans = Pembukuan::Where(['user_id' => Auth::user()->id])->OrderBy("id", "DESC")->paginate(2)->toArray();
        }
            $response = [
            "total_count" => $pembukuans["total"],
            "limit" => $pembukuans["per_page"],
            "pagination" => [
                "next_page" => $pembukuans["next_page_url"],
                "current_page" => $pembukuans["current_page"]
            ],
            "data" => $pembukuans["data"],
        ];
        /*$outPut = [
            "message" => "pembukuan",
            "results" => $pembukuans
        ];*/
        return response()->json($pembukuans, 200);
       /*$acceptHeader = $request->header('Accept');

        // Validasi: hanya application json atau xml yang valid
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $pembukuans = Pembukuan::OrderBy("id", "DESC")->paginate(10);

            if ($acceptHeader === 'application/json'){
                return response()->json($pembukuans->items('data'),200);
            } else {
                // create xml $pembukuans element
                $xml = new \SimpleXMLElement('<pembukuans/>');
                foreach ($pembukuans->items('data') as $item) {
                    // create xml $pembukuans element
                    $xmlItem = $xml->addChild('pembukuan');
                    //mengubah setiap field $pembukuan menjadi bentuk xml
                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('pembukuan_id', $item->pembukuan_id);
                    $xmlItem->addChild('tanggalpembukuan', $item->tanggalpembukuan);
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
            'tanggalpembukuan' => 'required|min:3'
        ];
        $validator = \Validator::make($input, $validationRules);
        if ($validator->fails()){
            return response()->json($validator->errors(), 400);
        }*/
        $pembukuans = Pembukuan::create($input);
        if (Gate::denies('store-pembukuans', $pembukuans)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }
        return response()->json($pembukuans, 200);
        /*
        $acceptHeader = $request->header('Accept');
        
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            
            $contentTypeHeader = $request->header('Content-Type');

            if ($contentTypeHeader === 'application/json' || $contentTypeHeader === 'application/xml') {
                $input = $request->all();
                $pembukuans = Pembukuan::create($input);

                return response()->json($pembukuans, 200);
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
        $pembukuans = Pembukuan::find($id);
        if (Gate::denies('show-pembukuans', $pembukuans)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }

        if(!$pembukuans) {
            abort(404);
        }
        return response()->json($pembukuans, 200);
        /*
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $pembukuans = Pembukuan::find($id);

            if(!$pembukuans) {
                abort(404);
            }
            return response()->json($pembukuans, 200);
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

        $pembukuans = Pembukuan::find($id);

        if(!$pembukuans) {
            abort(404);
        }
        if (Gate::denies('update-pembukuans', $pembukuans)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }
        /*// validation
        $validationRules = [
            'pemesanan_id' => 'required|exist:pemesanan,id',
            'tanggalpembukuan' => 'required|min:3'
        ];
        $validator = \Validator::make($input, $validationRules);
        if ($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        //validation end*/
        $pembukuans->fill($input);
        $pembukuans->save();

        return response()->json($pembukuans, 200);
        /*
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $contentTypeHeader = $request->header('Content-Type');

            if ($contentTypeHeader === 'application/json' || $contentTypeHeader === 'application/xml'){
                $input = $request->all();

                $pembukuans = Pembukuan::find($id);

                if(!$pembukuans) {
                    abort(404);
                }
                $pembukuans->fill($input);
                $pembukuans->save();

                return response()->json($pembukuans, 200); 
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
        $pembukuans = Pembukuan::find($id);

        if(!$pembukuans){
            abort(404);
        }
        if (Gate::denies('delete-pembukuans', $pembukuans)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }
        $pembukuans->delete();
        $message = ['message' => 'deleted successfully', 'pembukuan_id' => $id];
        /*
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $pembukuans = pembukuan::find($id);

            if(!$pembukuans){
                abort(404);
            }
            $pembukuans->delete();
            $message = ['message' => 'deleted successfully', 'pembukuan_id' => $id];
        } else {
            return response('Not Acceptable!', 406);
        }*/
    }
}
