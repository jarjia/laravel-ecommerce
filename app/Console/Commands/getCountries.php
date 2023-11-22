<?php

namespace App\Console\Commands;

use App\Models\Countries;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class getCountries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vue-ecommerce:get-countries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'with this command we get countries and save them in database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $getresponse = Http::get('https://restcountries.com/v3.1/independent');

        $countries = $getresponse->json();

        $countriesSorted = [];

        foreach ($countries as $country) {
            array_push($countriesSorted, [
                'country' => $country['name']['common'],
            ]);
        }
        function compareCountries($a, $b)
        {
            return strcmp($a['country'], $b['country']);
        }

        usort($countriesSorted, function ($a, $b) {
            return strcmp($a['country'], $b['country']);
        });

        foreach ($countriesSorted as $c) {
            Countries::updateOrCreate([
                'country' => $c['country']
            ]);
        }
    }
}
