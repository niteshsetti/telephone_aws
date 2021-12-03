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
$params = [
    'TableName' => $tableName,
];
if(isset($_POST["delete"])){
try {
    $result = $dynamodb->deleteTable($params);
    echo "Deleted items Successfully";

} catch (DynamoDbException $e) {
    echo "Unable to delete item:\n";
    echo $e->getMessage() . "\n";
}
}


?>