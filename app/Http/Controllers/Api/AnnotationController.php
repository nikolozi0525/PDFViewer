<?php

namespace App\Http\Controllers\Api;

use App\Models\Annotation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AnnotationController extends Controller
{
    //
    public function store(Request $request) {
        $data = $request->all();
        $userId = $data['userId'];
        $bookId = $data['bookId'];

        Annotation::updateOrCreate(['userId' => $userId, 'bookId' => $bookId], [
            'username' => $data['username'], 'annotations' => $data['annotations']
        ]);

        return response()->json(['message' => 'saved!'], 200);
    }
}