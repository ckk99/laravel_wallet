<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FlightBookingController extends Controller
{
    public function searchFlights()
    {
        // Dynamic date generation (you can modify this to any logic you need)
        $dateOfJourney = now()->addDays(7)->format('Y-m-d'); // Example: 7 days from today
        // API request using the Http facade
        //$response = Http::post('https://www.stagingapi.bdsd.technology/api/flightservice/rest/search', $data);

        $url = 'https://www.stagingapi.bdsd.technology/api/airservice/rest/search';
        // Make the POST request using Laravel's HTTP Client
        $data = [
            'UserIp' => request()->ip(), // Assuming this gets the user's IP address dynamically
            'DateOfJourney' => $dateOfJourney, // Change date as required
            'OriginId' => '123', // Replace with dynamic origin ID
            'DestinationId' => '456', // Replace with dynamic destination ID
        ];
        // Prepare the credentials
        $credentials = [
            'Username' => 'pranjal@bdsdtechnology.com',
            'Password' => '12345678',
            'Btype' => 'web',
        ];

        // Send POST request with JSON payload and custom headers
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Username' => $credentials['Username'],
            'Password' => $credentials['Password'],
            'Btype' => $credentials['Btype'],
        ])
        ->timeout(60) // Set timeout as 60 seconds
        ->connectTimeout(20) // Set connection timeout as 20 seconds
        ->post($url, $data);

        // Check if the response is successful
        if ($response->successful()) {
            // Process the response (e.g., return the result to the view)
            return response()->json($response->json());
        } else {
            // Handle errors, e.g., return an error message
            return response()->json([
                'error' => 'Request failed', 
                'message' => $response->body()
            ], 500);
        }
    
    }
}
