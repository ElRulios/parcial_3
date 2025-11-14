<?php
// DB.php
class DB {
    private static $instance = null;
    private $conexion;
    
    // Configuración
    private $host = '127.0.0.1';
    private $port = '3307'; 
    private $dbname = 'parcial3';
    private $usuario = 'root';
    private $password = '';
    private $charset = 'utf8mb4';
    
    private function __construct() {
        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->conexion = new PDO($dsn, $this->usuario, $this->password, $options);
        } catch(PDOException $e) {
            throw new Exception("Error de conexión: " . $e->getMessage());
        }
    }

    // Singleton
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conexion;
    }

    // Funciones CRUD para el parcial
    public function getCountries() {
        $stmt = $this->conexion->query("SELECT id, name FROM countries ORDER BY name");
        return $stmt->fetchAll();
    }

    public function getInterests() {
        $stmt = $this->conexion->query("SELECT id, name FROM interests ORDER BY name");
        return $stmt->fetchAll();
    }

    public function insertRegistrant($data) {
        // $data: associative array con keys: nombre, apellido, edad, sexo, country_id, nacionalidad, correo, celular, observaciones, fecha
        $sql = "INSERT INTO registrants
            (first_name, last_name, age, sexo, country_id, nationality, email, phone, observations, form_date)
            VALUES
            (:first_name, :last_name, :age, :sexo, :country_id, :nationality, :email, :phone, :observations, :form_date)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':age' => $data['age'],
            ':sexo' => $data['sexo'],
            ':country_id' => $data['country_id'],
            ':nationality' => $data['nationality'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':observations' => $data['observations'],
            ':form_date' => $data['form_date'],
        ]);
        return $this->conexion->lastInsertId();
    }

    public function insertRegistrantInterests($registrant_id, array $interest_ids) {
        $sql = "INSERT INTO registrant_interests (registrant_id, interest_id) VALUES (:registrant_id, :interest_id)";
        $stmt = $this->conexion->prepare($sql);
        foreach ($interest_ids as $iid) {
            $stmt->execute([
                ':registrant_id' => $registrant_id,
                ':interest_id' => $iid,
            ]);
        }
    }

    public function getReport() {
        $sql = "
            SELECT r.id, r.first_name, r.last_name, r.age, r.sexo, c.name as country, r.nationality, r.email, r.phone, r.observations, r.form_date,
                GROUP_CONCAT(i.name SEPARATOR ', ') as interests
            FROM registrants r
            LEFT JOIN countries c ON r.country_id = c.id
            LEFT JOIN registrant_interests ri ON r.id = ri.registrant_id
            LEFT JOIN interests i ON ri.interest_id = i.id
            GROUP BY r.id
            ORDER BY r.form_date DESC, r.id DESC
        ";
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll();
    }
}
