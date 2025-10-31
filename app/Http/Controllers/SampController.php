<?php

namespace App\Http\Controllers;

class SampController extends Controller
{
    public function info()
    {
        $ip = '104.234.180.52';
        $port = 26012;

        $query = $this->querySampPlayers($ip, $port);

        return response()->json([
            'status' => isset($query['error']) ? 'error' : 'success',
            'data' => $query,
        ]);
    }

    private function querySampPlayers($ip, $port)
    {
        $socket = @fsockopen('udp://' . $ip, $port, $errno, $errstr, 2);
        if (!$socket) {
            return ['error' => 'Tidak bisa connect ke server'];
        }

        stream_set_timeout($socket, 2);

        // kirim query "i" (info)
        fwrite($socket, "SAMP" .
            chr(strtok($ip, '.')) .
            chr(strtok('.')) .
            chr(strtok('.')) .
            chr(strtok('.')) .
            pack('S', $port) .
            "i"
        );

        $data = fread($socket, 2048);
        fclose($socket);

        if (!$data || strlen($data) < 15) {
            return ['error' => 'Server tidak merespon'];
        }

        $offset = 11; // header skip
        $password = ord($data[$offset++]); // skip password byte

        // ambil jumlah player
        $players = unpack('v', substr($data, $offset, 2))[1];
        $offset += 2;

        // ambil max player
        $maxPlayers = unpack('v', substr($data, $offset, 2))[1];

        return [
            'players' => $players,
            'max_players' => $maxPlayers,
        ];
    }
}
