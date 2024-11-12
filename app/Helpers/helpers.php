<?php 

function jsonResponse(string $messages = 'OK', $data = [], int $status = 200, $errors = []){
    return response()->json(compact('messages', 'data', 'status', 'errors'), $status);
}