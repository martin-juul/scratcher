<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Track;
use Illuminate\Http\Request;

class StreamController extends Controller
{
    public function file(Request $request, Track $track)
    {
        if (!file_exists($track->path)) {
            return response()->json(['error' => 'file_not_found'], 500);
        }

        if (!is_readable($track->path)) {
            return response()->json(['error' => 'file_not_readable'], 500);
        }

        $stream = static function () use ($track) {
            $source = fopen($track->path, 'rb');
            $dest = fopen('php://output', 'wb');

            stream_copy_to_stream($source, $dest);

            fclose($source);
            fclose($dest);
        };

        return response()->streamDownload($stream, "{$track->title}.{$track->file_format}", [
            'Content-Type'   => $track->mime_type,
            'Content-Length' => $track->file_size,
        ]);
    }
}
