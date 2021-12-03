<?php
date_default_timezone_set('UTC');
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
require 'vendor/autoload.php';
include 'fetchrecords.php';
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
    @$name=$request->username;
    @$number=$request->usernumber;
    $key = $marshaler->marshalJson('
        {
            "Id": ' . $id . '
        }
    ');
    
     
    $eav = $marshaler->marshalJson('
        {
            ":r":"'.$name.'",
            ":p": "'.$number.'"
        }
    ');
    
    $params = [
        'TableName' => $tableName,
        'Key' => $key,
        'UpdateExpression' => 
            'set username= :r ,usernumber=:p',
        'ExpressionAttributeValues'=> $eav,
        'ReturnValues' => 'UPDATED_NEW'
    ];
    
    try {
        $count=0;
        for($i=0;$i<count($empty);$i++){
            if($empty[$i]==$number){
                $count=$count+1;
            }
        }
        if($count==0){
        $result = $dynamodb->updateItem($params);
        echo "Updated item.\n";
        }
        else{
            echo "Number Already Exists !!! ";
        }
    
    } catch (DynamoDbException $e) {
        echo "Unable to update item:\n";
        echo $e->getMessage() . "\n";
    }
    

?>