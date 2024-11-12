<?php 

function JsonResponse($messages = 'OK', $data = [], $status = 200, $errors = []){
    return response()->json(compact('messages', 'data', 'status', 'errors'), $status);
}