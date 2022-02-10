<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Parser;

class ParserController extends ApiController
{
    public function index()
    {
        return $this->respond(Parser::all());
        return $this->respond(Parser::paginate(10));
    }

    public function parserYcombinator($param = false)
    {
        $url       = "https://news.ycombinator.com/";
        $arrResult = (new Parser)->getContentAll($url);
        if ($param) {
            return $arrResult;
        }

        return $this->respond($arrResult);
    }

    public function addYcombinator()
    {
        return $this->respond((new Parser)->addOrUpdateLines($this->parserYcombinator(true)));
    }

    public function updatePointsYcombinator()
    {
        $items     = Parser::all();
        $arrResult = [];
        foreach ($items as $item) {
            if (!$item->site) {
                continue;
            }
            $arrInfo  = [];
            $newPoint = (new Parser)->getContentItem($item->site);
            if ($newPoint) {
                $item->points = $newPoint;
                $item->save();
            }
            $arrInfo['id']      = $item->id;
            $arrInfo['id_item'] = $item->id_item;
            $arrInfo['title']   = $item->title;
            $arrInfo['link']    = $item->link;
            $arrInfo['site']    = $item->site;
            $arrInfo['points']  = $newPoint;
            $arrResult[]        = $arrInfo;
        }

        return $this->respond($arrResult);
    }

    public function updatePointYcombinator($idItem)
    {
        $item      = Parser::where('id_item', $idItem)->first();
        $arrResult = [];
        if (!$item->site) {
            return false;
        }
        $newPoint = (new Parser)->getContentItem($item->site);
        if ($newPoint) {
            $item->points = $newPoint;
            $item->save();
        }
        $arrResult['id']      = $item->id;
        $arrResult['id_item'] = $item->id_item;
        $arrResult['title']   = $item->title;
        $arrResult['link']    = $item->link;
        $arrResult['site']    = $item->site;
        $arrResult['points']  = $newPoint;

        return $this->respond($arrResult);
    }
}
