<?php

namespace App\Http\Controllers;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Illuminate\Support\Str;

class ScrapeController extends Controller
{
    public function link()
    {
        $url = config('app.dashboard_url');

        $client = HttpClient::create();
        $response = $client->request('GET', $url);
        $htmlContent = $response->getContent();

        $crawler = new Crawler($htmlContent);

        $link = $crawler->filter('div.tab-content > div > p.ql-align-center > a:contains("Groups")')->first();

        if ($link->count()) {
            $href = $link->attr('href');
            return $href;
        } else {
            dd("Groups link not found.");
        }
    }

    public function dashboard(string $group)
    {
        $href = $this->link();
        $url = $href ? $href : config('app.gtu_url');
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
