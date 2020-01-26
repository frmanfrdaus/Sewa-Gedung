<?php

namespace App\Http\Controllers\PublicController;

use App\Http\Controllers\Controller;
use App\Models\Gedung;
use Illuminate\Http\Request;

class GedungController extends Controller
{
    public function index(Request $request)
    {
        $gedung = Gedung::OrderBy("id", "DESC")->paginate(10)->toArray();
        $response = [
			"total_count" => $gedung["total"],
			"limit" => $gedung["per_page"],
			"pagination" => [
				"next_page" => $gedung["next_page_url"],
				"current_page" => $gedung["current_page"]
			],
			"data" => $gedung["data"],
		];

        return response()->json($response, 200);
    }

    public function show($id)
    {
        $gedung = Gedung::find($id);
        if (!$gedung) {
			abort(404);
		}
        return response()->json($gedung, 200);
    }
}
