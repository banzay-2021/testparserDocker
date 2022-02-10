<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parser extends Model
{

    use HasFactory;

    protected $table = 'parser';

    protected $fillable = [
        'title',
        'id_item',
        'link',
        'site',
        'points',
        'created'
    ];
    protected $guarded  = [
        'id',
        'created_at',
        'updated_at'
    ];

    public function addOrUpdateLine(array $array = [])
    {
        if (count($array) === 0) {
            return [];
        }

        return $this::updateOrCreate(
            ['id_item' => $item['id_item']],
            $item
        );
    }

    public function addOrUpdateLines(array $array = [])
    {
        $arrResult = [];
        if (count($array) === 0) {
            return $arrResult;
        }

        foreach ($array as $item) {
            $arrResult[] = $this::updateOrCreate(
                ['id_item' => $item['id_item']],
                $item
            );
        }

        return $arrResult;
    }

    public function getWebPage($url)
    {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_USERAGENT      => "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36",
            CURLOPT_AUTOREFERER    => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_SSL_VERIFYPEER => true
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $info    = curl_getinfo($ch);
        curl_close($ch);

        return $content;
    }

    public function getContentAll($url)
    {
        $html = $this->getWebPage($url);

        //include_once 'simple_html_dom.php';
        $html = str_get_html($html);

        $arrResult = [];
        $htmlPar   = $html->find('table.itemlist tr');
        if ($html->innertext != '' && count($htmlPar) > 0) {
            foreach ($htmlPar as $item) {
                if (!empty($item->id)) {
                    $arrInfo            = [];
                    $arrInfo['id_item'] = $item->id;
                    $arrInfo['title']   = trim($item->find('td.title a.titlelink', 0)->plaintext);
                    if ($item->find('td.title span.sitebit a', 0)) {
                        $arrInfo['link'] = trim($item->find('td.title a.titlelink', 0)->href);
                    } else {
                        $arrInfo['link'] = 'https://news.ycombinator.com/' . trim($item->find('td.title a.titlelink', 0)->href);
                    }
                    $arrInfo['site'] = 'https://news.ycombinator.com/item?id=' . $arrInfo['id_item'];
                } else if ($item->find('td.subtext span.score', 0)) {
                    $arrInfo['points']  = trim(str_replace(' points', '', $item->find('td.subtext span.score', 0)->plaintext));
                    $arrInfo['created'] = str_replace('T', ' ', trim($item->find('td.subtext span.age', 0)->attr['title']));
                    $arrResult[]        = $arrInfo;
                }
            }
        }
        $html->clear();

        return $arrResult;
    }

    public function getContentItem($url)
    {
        $html = $this->getWebPage($url);

        //include_once 'simple_html_dom.php';
        $html = str_get_html($html);
        if (!$html) {
            return false;
        }
        if ($html->innertext != '' && $html->find('table.itemlist tr', 0)) {
            $htmlPar = $html->find('table.itemlist tr');
        } else {
            $htmlPar = $html->find('table.fatitem tr');
        }
        if ($html->innertext != '' && count($htmlPar) > 0) {
            foreach ($htmlPar as $item) {
                if ($item->find('td.subtext span.score', 0)) {
                    return trim(str_replace(' points', '', $item->find('td.subtext span.score', 0)->plaintext));
                }
            }
        }
        $html->clear();

        return false;
    }
}
