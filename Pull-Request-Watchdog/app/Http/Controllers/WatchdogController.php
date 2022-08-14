<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class WatchdogController extends Controller
{

    public function getOldPullRequests()
    {
        $n = 2;
        $mydate = date("Y-m-d", strtotime("-2 week"));
        $mytime = date("H:i:s");
        $formattedDate = $mydate . "T" . $mytime . "Z";
        $headers = [
            "Accept:application/vnd.github+json", "User-Agent: Christopher-Yammine",
            "authorization: Bearer " . env("TOKEN")
        ];
        $fileLink = "";
        $filename = "./Downloads/1-old-pull-requests.csv";
        $output = "";
        for ($i = 1; $i < $n; $i++) {
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


            for ($j = 0; $j < count($pull_requests); $j++) {
                if ($pull_requests[$j]->created_at < $formattedDate) {
                    $output .= $pull_requests[$j]->title . " " . $pull_requests[$j]->created_at . "\n";
                }
            }


            if (count($pull_requests) === 100) {
                ++$n;
            }
        }

        file_put_contents($filename, $output);
        $rawLink = explode("./", $filename)[1];
        $fileLink .= env("DOWNLOADS_URL") . $rawLink;
        return response()->json([
            "status" => "success",
            "link" => $fileLink
        ], 200);
    }

    public function getRRPullRequests()
    {
        $n = 2;
        $fileLink = "";
        $headers = [
            "Accept:application/vnd.github+json", "User-Agent: Christopher-Yammine",
            "authorization: Bearer " . env("TOKEN")
        ];
        $filename = "./Downloads/2-review-required-pull-requests.csv";
        $output = "";
        for ($i = 1; $i < $n; $i++) {

            $url = env("BASE_URL") . $i;
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $resp = curl_exec($curl);
            curl_close($curl);
            $pull_requests = "";
            $pull_requests = json_decode($resp, false);

            for ($j = 0; $j < count($pull_requests); $j++) {
                if ($pull_requests[$j]->requested_reviewers != [] || $pull_requests[$j]->requested_teams != []) {
                    $output .= $pull_requests[$j]->title . "\n";
                }
            }

            if (count($pull_requests) === 100) {
                ++$n;
            }
        }
        file_put_contents($filename, $output);
        $rawLink = explode("./", $filename)[1];
        $fileLink .= env("DOWNLOADS_URL") . $rawLink;
        return response()->json([
            "status" => "success",
            "link" => $fileLink
        ], 200);
    }
    public function getSuccessPullRequests()
    {
        ini_set('max_execution_time', '300');
        $headers = [
            "Accept:application/vnd.github+json", "User-Agent: Christopher-Yammine",
            "authorization: Bearer " . env("TOKEN")
        ];
        $n = 2;
        $filename = "./Downloads/3-Successful-PRs.csv";
        $fileLink = "";
        $output = "";

        for ($i = 1; $i < $n; $i++) {

            $url = env("BASE_URL") . $i;
           
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $resp = curl_exec($curl);

            curl_close($curl);
            $pull_requests = "";
            $pull_requests = json_decode($resp, false);
            for ($j = 0; $j < count($pull_requests); $j++) {
                $url2 = "https://api.github.com/repos/woocommerce/woocommerce/commits/" . $pull_requests[$j]->head->sha . "/status";
                $curl2 = curl_init($url2);
                curl_setopt($curl2, CURLOPT_URL, $url2);
                curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl2, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl2, CURLOPT_HTTPHEADER, $headers);
                $resp2 = curl_exec($curl2);
              
                curl_close($curl2);
                $pull_requests2 = "";
                $pull_requests2 = json_decode($resp2, false);

                if ($pull_requests2->state == "success") {
                    $output .= $pull_requests[$j]->title . "\n";
                }
            }
            if (count($pull_requests) === 100) {
                ++$n;
            }
        }
        file_put_contents($filename, $output);
        $rawLink = explode("./", $filename)[1];
        $fileLink .= env("DOWNLOADS_URL") . $rawLink;
        return response()->json([
            "status" => "success",
            "link" => $fileLink
        ], 200);
    }
    public function getUnassignedPullRequests()
    {
        $filename = "./Downloads/4-Unassigned-PRs.csv";
        $fileLink = "";
        $output = "";
        $headers = [
            "Accept:application/vnd.github+json", "User-Agent: Christopher-Yammine",
            "authorization: Bearer " . env("TOKEN")
        ];

        $n = 2;
        for ($i = 1; $i < $n; $i++) {

            $url = env("BASE_URL") . $i;
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $resp = curl_exec($curl);

            curl_close($curl);
            $pull_requests = "";
            $pull_requests = json_decode($resp, false);


            for ($j = 0; $j < count($pull_requests); $j++) {
                if ($pull_requests[$j]->requested_reviewers == [] && $pull_requests[$j]->requested_teams == []) {
                    $output .= $pull_requests[$j]->title . "\n";
                }
            }
            if (count($pull_requests) === 100) {
                ++$n;
            }
        }
        file_put_contents($filename, $output);
        $rawLink = explode("./", $filename)[1];
        $fileLink .= env("DOWNLOADS_URL") . $rawLink;
        return response()->json([
            "status" => "success",
            "link" => $fileLink
        ], 200);
    }
}
