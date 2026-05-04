<?php
// echo FIREBASE_PLUGIN_DIR;die();
require LBB_ABS_URL.'/vendor/autoload.php';
/*require_once LBB_ABS_URL."vendor/google/auth/src/FetchAuthTokenCache.php";
require_once LBB_ABS_URL."vendor/google/auth/src/Middleware/AuthTokenMiddleware.php";
require_once LBB_ABS_URL."vendor/google/auth/src/HttpHandler/HttpHandlerFactory.php";*/

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
class Firebase_Users {
    protected $database;

    protected $ref = "users";

    public function __construct() {
        $firebase_credentials = getLBBAPPCredentials();


        if(!isset($firebase_credentials["firebase_app_config"])){
            return;
        }

        $validate = get_transient("rao_firebase_global_error");
        if("yes" === $validate){
            return;
        }
        $app_config = json_decode($firebase_credentials["firebase_app_config"], true);
        if(!isset($app_config["databaseURL"])){
            return;
        }
        
        $firebase_db_url = @$app_config["databaseURL"];
        if(!$firebase_db_url){
            return;
        }
        
        $db_config = @$firebase_credentials["firebase_db_config"];
        
        if(!$db_config){
            return;
        }

        $path = LBB_ABS_URL.'lib/firebase/db/firebase-db-cred.json';
        
        $fp = fopen($path, 'w');
        fwrite($fp, $db_config);
        fclose($fp);

        $new_data = json_encode($db_config, true);

        //$factory = (new Factory)->withServiceAccount($db_config);
        $factory = (new Factory)->withServiceAccount($path);
        $firebase = (new Factory)
        ->withServiceAccount($path)
        ->withDatabaseUri($firebase_db_url);

        $fp = fopen($path, 'w');
        fwrite($fp, '');
        fclose($fp);

        try {
        $this->database = $firebase->createDatabase();
        } catch (Exception $e) {
       // write_log("underdatabase failed");
        }
        
    }

    public function lbbAuth($uid) {
        $firebase_credentials = getLBBAPPCredentials();

        $path = LBB_ABS_URL.'lib/firebase/db/firebase-db-cred.json';

        $db_config = @$firebase_credentials["firebase_db_config"];
        
        if(!$db_config){
            return;
        }

        $path = LBB_ABS_URL.'lib/firebase/db/firebase-db-cred.json';
        
        $fp = fopen($path, 'w');
        fwrite($fp, $db_config);
        fclose($fp);

        $factory = (new Factory)->withServiceAccount($path);


        $fp = fopen($path, 'w');
        fwrite($fp, '');
        fclose($fp);

        $auth = $factory->createAuth();

        // Authenticate the user and get the ID token
        $customToken = $auth->createCustomToken($uid);

    
        // Send the token to the client
        return $customToken->toString();
    }

    public function insert(array $data) {
        if($this->database === null)
        return;
        if (empty($data) || !isset($data)) { return FALSE; }
        /*foreach ($data as $key => $value){
            $this->database->getReference()->getChild($this->ref)->getChild($key)->set($value);
        }*/
        $pushData = $this->database->getReference($this->ref)->push($data);
        
        $user_key = $pushData->getKey();
        return $user_key;
    }

    public function update( array $data ) {
        if($this->database === null)
        return;
        if (empty($data) || !isset($data)) { return FALSE; }
        $this->database->getReference()->update($data);

    }

    public function getLists() {
        $pushData = $this->database->getReference($this->ref)->getChildKeys();
        /*$count = 0;
        foreach($pushData as $key => $childkey) {
            $count++;
            $single_node = $this->database->getReference($this->ref)->getSnapshot()->getChild($childkey)->getValue();
            if($count > 0)
            break;
        }*/
        return $pushData;
    }

    public function getSpecificChildData( $childkey ) {
        $single_node = $this->database->getReference($this->ref)->getSnapshot()->getChild( $childkey )->getValue();
        return $single_node;
    }
 }

?>