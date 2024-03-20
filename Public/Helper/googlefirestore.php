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

function data_set_from_map(array $data, string $collection): array
{
    // Create the Cloud Firestore client
    $db = init_FirestoreClient();
    # [START firestore_data_set_from_map]

    /*Array ( [ID] => test1 [username] => test test1 [password] => $2y$10$.7KlaoEIqJJmWMMMJAb/s.rELkADYkORN63z/4yGsuF7GNlZ1jN6q [UserImage] => [Register] => Register )*/
    if ($collection === 'UserAccount') {
        $db->collection($collection)->document($data['ID'])->set($data);
    } else {
        $addedDocRef = $db->collection($collection)->newDocument();
        $addedDocRef->set($data);
        debug_to_console($addedDocRef->id());
    }
}

function get_random_docid(string $collection): string
{
    // Create the Cloud Firestore client
    $db = init_FirestoreClient();
    # [START firestore_data_set_from_map]

    $addedDocRef = $db->collection($collection)->add();
    debug_to_console($addedDocRef->id());
    return $addedDocRef->id();
}

function Set_DocID_Data(array $data, string $collection, string $docid): void
{
    $db = init_FirestoreClient();
    $Ref = $db->collection($collection)->document($docid);
    $Ref->set($data);
}
