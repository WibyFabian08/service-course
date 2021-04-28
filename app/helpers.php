<?php

use Illuminate\Support\Facades\Http;

function getUser($userId) {
    try {
        $response = Http::timeout(10)->get('http://localhost:5000/users/'.$userId);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch(\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service user unavailable'
        ];
    }
}

function getUserByIds($userIds = []) {
    try {
        if(count($userIds) === 0) {
            return [
                'status' => 'success',
                'http_code' => 200,
                'data' => []
            ];   
        }

        $response = Http::timeout(10)->get('http://localhost:5000/users/', ['user_ids[]' => $userIds]);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;

    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service user unavailable'
        ];
    }
}

function postOrder($params) {
    try {
        $response = Http::post('http://localhost:8001/api/orders', $params);
        $data = $response->json();
        return $data;
    } catch(\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service order payment unavailable'
        ];
    }
}


?>