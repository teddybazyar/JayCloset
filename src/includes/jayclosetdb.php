<?PHP

class jayclosetdb{
    private static $conn = null;

    private static function connect() {
        global $host, $database, $dbUsername, $dbPassword;
           
        if (self::$conn === null) {
            try {
                self::$conn = new PDO("mysql:host=$host;dbname=$database", $dbUsername, $dbPassword);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
                http_response_code(500);
                exit();
            }
        }
    }

    public static function getDataFromSQL($sql,$params=null){
        self::connect();
        
        $stmt = self::$conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $valuesArray = $stmt->fetchAll();
        return $valuesArray;
    }

    public static function executeSQL($sql,$params=null,$returnID=false){
        self::connect();
        
        $stmt = self::$conn->prepare($sql);
        $stmt->execute($params);

        if ($returnID) {
            return self::$conn->lastInsertId();
        } else {
            return true;
        }
    }

    public static function startTransaction() {
        self::connect();
        self::$conn->beginTransaction();
    }

    public static function commitTransaction() {
        self::$conn->commit();
    }

    public static function rollbackTransaction() {
        self::$conn->rollback();
    }
}
?>