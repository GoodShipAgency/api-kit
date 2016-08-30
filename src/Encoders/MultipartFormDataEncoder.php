<?php

namespace Mashbo\Components\ApiKit\Encoders;

class MultipartFormDataEncoder
{
    public function encode($data, $boundary)
    {
        $encoded = '';

        // To support large files, this could make better use of streams
        foreach ($data as $key => $value) {

            $encoded .= "--$boundary\r\nContent-disposition: form-data; ";

            switch (true) {
                case is_scalar($value):
                    $encoded .= "name=\"$key\"\r\n\r\n$value\r\n";
                    break;
                case is_array($value) && array_key_exists('type', $value) && $value['type'] == 'file':
                    $encoded .= "name=\"$key\"; filename=\"{$value['filename']}\"\r\nContent-type: {$value['mimeType']}\r\n\r\n{$value['contents']}\r\n";
                    break;
                default:
                    throw new \LogicException("Unhandled data type to be encoded as multipart/form-data");
            }
        }

        $encoded .= "--$boundary--\r\n";

        return $encoded;
    }
}