<?php
require '../vendor/autoload.php';
require_once ("../public/Helper/helper.php");
use Google\Cloud\Firestore\FirestoreClient;

/**
 * Initialize Cloud Firestore with default project ID.
 */


function init_FirestoreClient()
{

    $projectId = 's3273504-a1t1';
    // Create the Cloud Firestore client
    $db = new FirestoreClient([
        'projectId' => $projectId,
    ]);

    return $db;

}

function data_query(string $collection)
{
    $db = init_FirestoreClient();
    # [START firestore_data_query]
    $UserAccounts = $db->collection($collection);
    $query = $UserAccounts;
    $documents = $query->documents();
    return $documents;
    # [END firestore_data_query]
}

function data_set_from_map(array $data, string $collection): void
{
    // Create the Cloud Firestore client
    $db = init_FirestoreClient();
    # [START firestore_data_set_from_map]
    debug_to_console('init_FirestoreClient: ');
    /*Array ( [ID] => test1 [username] => test test1 [password] => $2y$10$.7KlaoEIqJJmWMMMJAb/s.rELkADYkORN63z/4yGsuF7GNlZ1jN6q [UserImage] => [Register] => Register )*/
    $upload = [
        'password' => $data['password'],
        'user_name' => $data['username'],
        'image_path' => $data['UploadPath']
    ];
    $db->collection($collection)->document($data['ID'])->set($upload);
}