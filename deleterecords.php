<?php
date_default_timezone_set('UTC');
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
require 'vendor/autoload.php';
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
$sdk = new Aws\Sdk([
        'region'   => 'us-east-2',
        'version'  => 'latest',
        'credentials' => array(
            'key' => 'AKIAT5DHJ6SDMPC6JQ5T',
            'secret' => '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'
        )
    ]);

$dynamodb = $sdk->createDynamoDb();
$marshaler = new Marshaler();
$tableName = 'Telephone';
$postdata=file_get_contents("php://input");
$request=json_decode($postdata);
@$id=$request->Id;
echo $id;
$key = $marshaler->marshalJson('
    {
        "Id":' . $id . '
    }
');
print_r($key);
$params = [
    'TableName' => $tableName,
    'Key' => $key
];

try {
    $result = $dynamodb->deleteItem($params);
    echo "Deleted item.\n";

} catch (DynamoDbException $e) {
    echo "Unable to delete item:\n";
    echo $e->getMessage() . "\n";
}


?>