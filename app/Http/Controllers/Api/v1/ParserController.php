<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Parser;
use App\Services\ParserServise;
use Illuminate\Http\Request;

class ParserController extends ApiController
{
    public function index(Request $request)
    {
        return $request->ajax() ? Parser::orderBy('id', 'desc')->paginate(10) : abort(404);
    }

    public function parserYcombinator($param = false)
    {
        $url       = "https://news.ycombinator.com/";
        $arrResult = (new ParserServise)->getContentAll($url);
        if ($param) {
            return $arrResult;
        }

        return $this->respond($arrResult);
    }

    public function addYcombinator()
    {
        return $this->respond((new ParserServise)->addOrUpdateLines($this->parserYcombinator(true)));
    }

    public function updatePointsYcombinator()
    {
        return $this->respond((new ParserServise)->updateAllPointsYcombinator());
    }

    public function updatePointYcombinator($idItem)
    {
        return $this->respond((new ParserServise)->updatePointsYcombinator($idItem));
    }
}
