<?php

namespace App\Http\Controllers;

use App\Models\Parser;

class ParserController extends Controller
{
    public function index()
    {
        //return $this->respond(Parser::all());
        return $this->respond(Parser::paginate(10));
    }
}
