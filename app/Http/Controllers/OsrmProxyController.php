<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OsrmProxyController extends Controller
{
    public function getRoute(Request $request)
    {
        // Ambil parameter koordinat dari frontend
        $coordinates = $request->input('coordinates');

        if (!$coordinates) {
            return response()->json(['error' => 'Coordinates parameter is required'], 400);
        }

        // Base URL OSRM API
        $baseUrl = 'https://router.project-osrm.org/route/v1/driving/' . $coordinates;

        // Tambahkan opsi default jika tidak dikirim dari frontend
        $query = [
            'overview' => $request->input('overview', 'false'),
            'alternatives' => $request->input('alternatives', 'true'),
            'steps' => $request->input('steps', 'true'),
        ];

        try {
            $response = Http::timeout(10)->get($baseUrl, $query);

            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['error' => 'OSRM API error', 'status' => $response->status()], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Request failed', 'message' => $e->getMessage()], 500);
        }
    }
}
