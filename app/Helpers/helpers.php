<?php 

/**
 * Generates a standardized JSON response for the API.
 * 
 * @param string $message The main message of the response.
 * @param mixed $data The data to be returned in the response (array or object).
 * @param int $statusCode The HTTP status code (default is 200).
 * @param mixed $errors Display errors datails if any 
 * 
 * @return \Illuminate\Http\JsonResponse
*/
function jsonResponse(string $message = 'OK', $data = [], int $status = 200, $errors = []){
    return response()->json(compact('message', 'data', 'status', 'errors'), $status);
}