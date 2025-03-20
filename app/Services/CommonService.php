<?php

namespace App\Services;
class CommonService
{
    private function generateUserId(): string
    {
        return "USER-" . strtoupper(bin2hex(random_bytes(4))); // Example: USER-8F4A3C9D
    }

    public function uploadImage($file, $fileId, $path)
    {
        

    }
}