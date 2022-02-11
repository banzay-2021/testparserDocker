<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Parser;
use App\Services\ParserServise;

class ParserController extends ApiController
{
    public function index()
    {
        //return $this->respond(Parser::all());
        return $this->respond(Parser::paginate(10));
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
