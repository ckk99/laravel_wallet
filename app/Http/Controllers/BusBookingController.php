<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class BusBookingController extends Controller
{
    /**
     * Search for buses.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchBus()
    {
        // Dynamic date generation (you can modify this to any logic you need)
        $dateOfJourney = now()->addDays(7)->format('Y-m-d'); // Example: 7 days from today
        
        // $url = 'https://www.stagingapi.bdsd.technology/api/busservice/rest/search';
        $url = 'https://www.api.bdsd.technoloy/api/busservice/rest/search';
        // Make the POST request using Laravel's HTTP Client
        $data = [
            'UserIp' => request()->ip(), // Assuming this gets the user's IP address dynamically
            'DateOfJourney' => $dateOfJourney, // Change date as required
            'OriginId' => '123', // Replace with dynamic origin ID
            'DestinationId' => '456', // Replace with dynamic destination ID
        ];
        $credentials = [
            'Username' => 'ABAFK0044M',
            'Password' => '7830285',
            'Btype' => 'api',
        ];

        $curl = curl_init();

        // // Data to send in the POST request (example data, adjust as needed)
        // $data = [
        //     'key1' => 'value1', // Replace with actual data fields
        //     'key2' => 'value2'
        // ];

        // Encode the data into JSON format
        $json_data = json_encode($data);

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.api.bdsd.technology/api/busservice/rest/search',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Username: ABAFK0044M',
                'Password: 7830285',
                'Btype: api',
                'Content-Type: application/json',
                'Cookie: tts_api_session=ba569c80fc3a0edb7f0f36530bfe4aad2c0d0a4c'
            ),
            CURLOPT_POSTFIELDS => $json_data, // Add the JSON data to the POST body
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;



        // $request_json = json_encode($data);
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        // curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $request_json);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Username:' . $credentials['Username'] . '', 'Password:' . $credentials['Password'] . '', 'Btype:' . $credentials['Btype'] . ''));
        // $response = curl_exec($ch);
        // curl_close($ch);
        // return json_decode($response, true);
        // Make the POST request using Laravel's HTTP Client
        // $response = Http::withHeaders([
        //     'Content-Type' => 'application/json',
        //     'Username' => $credentials['Username'],
        //     'Password' => $credentials['Password'],
        //     'Btype' => $credentials['Btype'],
        // ])
        // ->timeout(60) // Set timeout as 60 seconds
        // ->connectTimeout(20) // Set connection timeout as 20 seconds
        // ->post($url, $data);
        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json([
                'error' => 'Request failed', 
                'message' => $response->json()
            ], 500);
        }
    
    }

}
