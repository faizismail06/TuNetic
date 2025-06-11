<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class OsrmProxyController extends Controller
{
    public function proxyOsrmRequest(Request $request, $path = null)
    {
        try {
            // Debugging
            Log::info('OSRM Proxy Request', ['path' => $path, 'query' => $request->all()]);

            $client = new Client();

            // Bangun URL yang benar dengan memastikan format yang tepat
            $baseUrl = 'https://router.project-osrm.org/';

            // Pastikan path selalu mengandung 'route/v1'
            if ($path && !str_contains($path, 'route/v1')) {
                if (str_starts_with($path, 'driving')) {
                    $path = 'route/v1/' . $path;
                }
            }

            $fullUrl = $baseUrl . ($path ?: '');
            Log::info('OSRM URL', ['url' => $fullUrl]);

            // Lakukan request dengan timeout yang cukup
            $response = $client->request('GET', $fullUrl, [
                'query' => $request->query(),
                'timeout' => 60,
                'connect_timeout' => 10,
            ]);

            // Kembalikan response
            return response($response->getBody())
                ->withHeaders([
                    'Content-Type' => $response->getHeaderLine('Content-Type') ?: 'application/json',
                    'Access-Control-Allow-Origin' => '*',
                ]);
        } catch (RequestException $e) {
            Log::error('OSRM Proxy Error', [
                'message' => $e->getMessage(),
                'url' => $fullUrl ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'url' => $fullUrl ?? 'unknown',
            ], 500);
        } catch (\Exception $e) {
            Log::error('OSRM Proxy General Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Kesalahan server: ' . $e->getMessage(),
            ], 500);
        }
    }
}
