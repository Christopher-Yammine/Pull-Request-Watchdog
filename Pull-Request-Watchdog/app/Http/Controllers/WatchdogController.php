<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WatchdogController extends Controller
{
    public function getPullRequests()
    {
        for ($i = 1; $i <= 3; $i++) {

            $url = "https://api.github.com/repos/woocommerce/woocommerce/pulls?per_page=100&page=" . $i;
            

            $headers = [
                "Accept:application/vnd.github+json", "User-Agent: Christopher-Yammine"
            ];
            $curl = curl_init($url);


            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            //for debug only!
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $resp = curl_exec($curl);
            
            curl_close($curl);
            $pull_requests = "";
            $pull_requests = json_decode($resp, false);


            for ($j = 0; $j < count($pull_requests); $j++) {
                echo $pull_requests[$j]->url . "\n";
            }
            print_r(count($pull_requests));

            // $mydate = date("Y-m-d", strtotime("-2 week"));
            // print_r($mydate);
        }
    }
}
