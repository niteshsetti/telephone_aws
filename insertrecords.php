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
   if(isset($_POST["cid"]) ||isset($_POST["name"]) || isset($_POST["phnumber"])){
    $index =$_POST["cid"];
    $name = $_POST["name"];
    $number=$_POST["phnumber"];
    $item = $marshaler->marshalJson('
        {
            "Id": ' . $index . ',
            "username": "' . $name . '",
            "usernumber": "' . $number . '"
        }
    ');

    $params = [
        'TableName' => $tableName,
        'Item' => $item
    ];

    try {
        $count=0;
        $count1=0;
        for($i=0;$i<count($empty);$i++){
            if($empty[$i]==$number){
                $count=$count+1;
            }
            if($snos[$i]==$index){
                $count1=$count1+1;
            }
        }
        if($count==0 && $count1==0){
            $result = $dynamodb->putItem($params);
            if($result){
                echo "Contact Saved Successfully";
            }
        }
        else if($count1!=0){
            echo "Contact ID Exists";
        }
        else{
           echo "Number Already Exists !!!";
        }
       


    } catch (DynamoDbException $e) {
        echo "Unable to add item:\n";
        echo $e->getMessage() . "\n";
    }

}
?>