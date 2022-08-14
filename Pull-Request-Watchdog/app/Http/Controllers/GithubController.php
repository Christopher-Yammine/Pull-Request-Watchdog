<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GithubController extends Controller
{
   
    public function urlCurl($i)
    {
        $n = 2;
        $headers = [
            "Accept:application/vnd.github+json", "User-Agent: Christopher-Yammine",
            "authorization: Bearer " . env("TOKEN")
        ];
        $url = env("BASE_URL") . $i;
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

        if (count($pull_requests) === 100) {
            ++$n;
        }
        return [$n, $pull_requests];
    }
}
