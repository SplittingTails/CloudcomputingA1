<?php
require '../vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;

function upload_object(string $bucketName, string $objectName, string $source): string
{
    $storageURL = 'https://storage.cloud.google.com';

    $storage = new StorageClient();
    if (!$file = fopen($source, 'r')) {
        throw new \InvalidArgumentException('Unable to open file for reading');
    }
    $bucket = $storage->bucket($bucketName);
    $object = $bucket->upload($file, [
        'name' => $objectName
    ]);

    return $storageURL .'/'. $bucketName .'/' . $objectName;

}
?>