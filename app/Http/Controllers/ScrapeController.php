<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;
use Laravel\Dusk\Browser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Illuminate\Support\Str;

class ScrapeController extends Controller
{
    public function dashboard(string $group)
    {
        $url = config('app.gtu_url');
        $client = new HttpClient();
        $httpclient = $client->create()->request('GET', $url);

        $htmlContent = $httpclient->getContent();
        $targetString = '<th colspan="6">'.$group.'</th>';
        $html = '';

        foreach(explode(' <table ', $htmlContent) as $node) {
            if(Str::contains($node, $targetString)) {
                $html = '<table '.explode('<p class="back">', $node)[0];
            }
        }

        return view('dashboard', [
            'html' => $html,
        ]);
    }
}
