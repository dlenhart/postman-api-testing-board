<?php

namespace App\Helpers;

class File
{
    public static function extractRunInfo($file): array
    {
        $f = explode('.', $file->getClientOriginalName());

        return [
            'application' => $f[0],
            'branch'      => $f[1],
            'extension'   => end($f)    
        ];        
    }
}