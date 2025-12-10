<?php

namespace App\Services;

use Illuminate\Filesystem\Filesystem as BaseFilesystem;

class CustomFilesystem extends BaseFilesystem
{
    /**
     * Write the contents of a file.
     *
     * @param  string  $path
     * @param  string  $contents
     * @param  bool  $lock  (এই প্যারামিটারটি আমরা উপেক্ষা করব)
     * @return int|bool
     */
    public function put($path, $contents, $lock = false)
    {
        // আমরা এখানে $lock প্যারামিটারটি ব্যবহার না করে সরাসরি লকিং ছাড়াই ফাইল লিখছি।
        return file_put_contents($path, $contents);
    }
}