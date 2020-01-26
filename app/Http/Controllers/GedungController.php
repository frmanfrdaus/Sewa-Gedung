<?php 

namespace App\Http\Controllers;

use App\Models\Gedung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class GedungController extends Controller
{
    /**
    * Display a listing of the resource.
    * 
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        if (Gate::denies('read-gedungs')) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }
        if (Auth::user()->role === 'admin') {
            // kalau admin, akan query semua pemesanan record
            $gedungs = Gedung::OrderBy("id", "DESC")->paginate(2)->toArray();
        } else {
            // kalau bukan admin, hanya akan menampilkan query berdasarkan user login
            $gedungs = Gedung::Where(['user_id' => Auth::user()->id])->OrderBy("id", "DESC")->paginate(2)->toArray();
        }
        $response = [
            "total_count" => $gedungs["total"],
            "limit" => $gedungs["per_page"],
            "pagination" => [
                "next_page" => $gedungs["next_page_url"],
                "current_page" => $gedungs["current_page"]
            ],
            "data" => $gedungs["data"],
        ];
        /*$outPut = [
            "message" => "gedung",
            "results" => $gedungs
        ];*/
        return response()->json($gedungs, 200);
        
        /*$acceptHeader = $request->header('Accept');

        // Validasi: hanya application json atau xml yang valid
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $gedungs = Gedung::OrderBy("id", "DESC")->paginate(10);

            if ($acceptHeader === 'application/json'){
                return response()->json($gedungs->items('data'),200);
            } else {
                // create xml gedungs element
                $xml = new \SimpleXMLElement('<gedungs/>');
                foreach ($gedungs->items('data') as $item) {
                    // create xml gedungs element
                    $xmlItem = $xml->addChild('gedung');
                    //mengubah setiap field gedung menjadi bentuk xml
                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('namagedung', $item->namagedung);
                    $xmlItem->addChild('alamat', $item->alamat);
                    $xmlItem->addChild('harga', $item->harga);
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
            'namagedung' => 'required|min:5',
            'alamat' => 'required|min:5',
            'harga' => 'required|min:3'
        ];
        $validator = \Validator::make($input, $validationRules);
        if ($validator->fails()){
            return response()->json($validator->errors(), 400);
        }*/
        
        $gedungs = Gedung::create($input);

        if (Gate::denies('store-gedungs', $gedungs)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }
        return response()->json($gedungs, 200);
        /*$acceptHeader = $request->header('Accept');
        
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            
            $contentTypeHeader = $request->header('Content-Type');

            if ($contentTypeHeader === 'application/json' || $contentTypeHeader === 'application/xml') {
                $input = $request->all();
                $gedungs = Gedung::create($input);

                return response()->json($gedungs, 200);
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
     * @param int $id_gedung
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $gedungs = Gedung::find($id);
        if (Gate::denies('show-gedungs', $gedungs)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }
        if(!$gedungs) {
            abort(404);
        }
        return response()->json($gedungs, 200);
        /*
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $gedungs = Gedung::find($id);

            if(!$gedungs) {
                abort(404);
            }
            return response()->json($gedungs, 200);
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

        $gedungs = Gedung::find($id);

        if(!$gedungs) {
            abort(404);
        }
        if (Gate::denies('update-gedungs', $gedungs)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }
        // validation
        /*$validationRules = [
            'namagedung' => 'required|min:5',
            'alamat' => 'required|min:5',
            'harga' => 'required|min:3'
        ];
        $validator = \Validator::make($input, $validationRules);
        if ($validator->fails()){
            return response()->json($validator->errors(), 400);
        }*/
        //validation end
        $gedungs->fill($input);
        $gedungs->save();

        return response()->json($gedungs, 200);
        /*
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $contentTypeHeader = $request->header('Content-Type');

            if ($contentTypeHeader === 'application/json' || $contentTypeHeader === 'application/xml'){
                $input = $request->all();

                $gedungs = Gedung::find($id);

                if(!$gedungs) {
                    abort(404);
                }
                $gedungs->fill($input);
                $gedungs->save();

                return response()->json($gedungs, 200); 
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
        $gedungs = Gedung::find($id);

        if(!$gedungs){
            abort(404);
        }
        if (Gate::denies('delete-gedungs', $gedungs)) {
            return response()->json([
                'success' => false,
                'status' => 403,
                'message' => 'You are unauthorized'
            ], 403);
        }
        $gedungs->delete();
        $message = ['message' => 'deleted successfully', 'gedung_id' => $id];
        return response()->json($message, 200);
        /*$acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml'){
            $gedungs = Gedung::find($id);

            if(!$gedungs){
                abort(404);
            }
            $gedungs->delete();
            $message = ['message' => 'deleted successfully', 'gedung_id' => $id];
        } else {
            return response('Not Acceptable!', 406);
        }*/
    }
}