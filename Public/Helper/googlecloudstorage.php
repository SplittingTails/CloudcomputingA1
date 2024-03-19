<?php
require '../vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;

function upload_object(string $objectName, string $source): string
{
    $bucketName = 's3273504userimages';
    $storageURL = 'https://storage.cloud.google.com';

    $storage = new StorageClient();
    if (!$file = fopen($source, 'r')) {
        throw new \InvalidArgumentException('Unable to open file for reading');
    }
    $bucket = $storage->bucket($bucketName);
    $object = $bucket->upload($file, [
        'name' => $objectName
    ]);

    printf('Uploaded %s to gs://%s/%s' . PHP_EOL, basename($source), $bucketName, $objectName);

    return $storageURL .'/'. $bucketName .'/' . $objectName;

}
?>