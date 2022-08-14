<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

ini_set('max_execution_time', '300');

class WatchdogController extends Controller
{

    public function get14DaysPullRequests()
    {
        $n = 2;
        $mydate = date("Y-m-d", strtotime("-2 week"));
        $mytime = date("H:i:s");
        $formattedDate = $mydate . "T" . $mytime . "Z";
        $headers = [
            "Accept:application/vnd.github+json", "User-Agent: Christopher-Yammine",
            "authorization: Bearer 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"
        ];
        $filename = "./1-old-pull-requests.txt";
        $output = "";
        for ($i = 1; $i < $n; $i++) {

            $url = "https://api.github.com/repos/woocommerce/woocommerce/pulls?&per_page=100&page=" . $i;



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
        echo "Done registering old PRs";
    }

    public function getRRPullRequests()
    {
        $n = 2;

        $headers = [
            "Accept:application/vnd.github+json", "User-Agent: Christopher-Yammine",
            "authorization: Bearer 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"
        ];
        $filename = "./2-review-required-pull-requests.txt";
        $output = "";
        for ($i = 1; $i < $n; $i++) {

            $url = "https://api.github.com/repos/woocommerce/woocommerce/pulls?&per_page=100&page=" . $i;



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
                if ($pull_requests[$j]->requested_reviewers != [] || $pull_requests[$j]->requested_teams != []) {
                    $output .= $pull_requests[$j]->title . "\n";
                }
            }

            if (count($pull_requests) === 100) {
                ++$n;
            }
        }
        file_put_contents($filename, $output);
        echo "Done registering review required PRs";
    }
    public function getSuccessPullRequests()
    {
        $headers = [
            "Accept:application/vnd.github+json", "User-Agent: Christopher-Yammine",
            "authorization: Bearer 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"
        ];
        $n = 2;
        $filename = "./3-Successful-PRs.txt";
        $output = "";

        for ($i = 1; $i < $n; $i++) {

            $url = "https://api.github.com/repos/woocommerce/woocommerce/pulls?&per_page=100&page=" . $i;



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


                $url2 = "https://api.github.com/repos/woocommerce/woocommerce/commits/" . $pull_requests[$j]->head->sha . "/status";



                $curl2 = curl_init($url2);


                curl_setopt($curl2, CURLOPT_URL, $url2);
                curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);

                //for debug only!
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
        echo "Done registering PRs with success status";
    }
    public function getUnassignedPullRequests()
    {
        $filename = "./4-Unassigned-PRs.txt";
        $output = "";
        $headers = [
            "Accept:application/vnd.github+json", "User-Agent: Christopher-Yammine",
            "authorization: Bearer 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"
        ];
        $n = 2;


        for ($i = 1; $i < $n; $i++) {

            $url = "https://api.github.com/repos/woocommerce/woocommerce/pulls?&per_page=100&page=" . $i;



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
                if ($pull_requests[$j]->requested_reviewers == [] && $pull_requests[$j]->requested_teams == []) {
                    $output.=$pull_requests[$j]->title . "\n";
                   
                }
            }
            if (count($pull_requests) === 100) {
                ++$n;
            }
        }
        file_put_contents($filename, $output);
        echo "Done registering unassigned PRs";
    }
}
