<?php
// process.php
require_once 'DB.php';

function clean_text($s) {
    return trim($s);
}

function capitalize_name($s) {
    // Asegura la primera letra en mayúscula (multibyte)
    return mb_convert_case(mb_strtolower($s, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
}

$errors = [];

// Requerimos método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Método no permitido.";
    exit;
}

// Recoger y sanitizar
$first_name = isset($_POST['first_name']) ? clean_text($_POST['first_name']) : '';
$last_name  = isset($_POST['last_name']) ? clean_text($_POST['last_name']) : '';
$age_raw    = isset($_POST['age']) ? $_POST['age'] : '';
$sexo       = isset($_POST['sexo']) ? $_POST['sexo'] : '';
$country_id = isset($_POST['country_id']) ? intval($_POST['country_id']) : null;
$nationality= isset($_POST['nationality']) ? clean_text($_POST['nationality']) : '';
$interests  = isset($_POST['interests']) && is_array($_POST['interests']) ? $_POST['interests'] : [];
$observations = isset($_POST['observations']) ? trim($_POST['observations']) : '';
$email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : null;
$phone = isset($_POST['phone']) ? preg_replace('/[^0-9\+\-\s]/','', $_POST['phone']) : null;
$form_date = date('Y-m-d');

// Validaciones servidor
if ($first_name === '') $errors[] = "El nombre es requerido.";
if ($last_name === '') $errors[] = "El apellido es requerido.";
if ($age_raw === '') $errors[] = "La edad es requerida.";
else {
    if (!filter_var($age_raw, FILTER_VALIDATE_INT)) $errors[] = "Edad debe ser un número entero.";
    else {
        $age = intval($age_raw);
        if ($age < 0 || $age > 120) $errors[] = "Edad inválida.";
    }
}
if ($sexo === '' || !in_array($sexo, ['M','F','O'])) $errors[] = "Selecciona un sexo válido.";
if ($country_id === null || $country_id <= 0) $errors[] = "Selecciona un país válido.";
if ($nationality === '') $errors[] = "La nacionalidad es requerida.";

// Email opcional pero si viene validarlo
if ($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El correo no tiene un formato válido.";
    }
}

// Interests: opcional, pero filtrar los ids a enteros
$interest_ids = [];
foreach ($interests as $i) {
    $i = intval($i);
    if ($i > 0) $interest_ids[] = $i;
}

// Si hay errores, los mostramos (puedes mejorar mostrando en formulario)
if (!empty($errors)) {
    // Mostrar errores en un formato simple
    echo "<h2>Se encontraron errores:</h2><ul>";
    foreach($errors as $e) {
        echo "<li>".htmlspecialchars($e)."</li>";
    }
    echo "</ul><p><a href='index.php'>Volver al formulario</a></p>";
    exit;
}

// Normalizar nombres (empiecen en mayúscula)
$first_name = capitalize_name($first_name);
$last_name  = capitalize_name($last_name);
$nationality = capitalize_name($nationality);

// Insertar en DB
try {
    $db = DB::getInstance();
    $data = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'age' => $age,
        'sexo' => $sexo,
        'country_id' => $country_id,
        'nationality' => $nationality,
        'email' => $email,
        'phone' => $phone,
        'observations' => $observations,
        'form_date' => $form_date
    ];
    $registrant_id = $db->insertRegistrant($data);
    if (!empty($interest_ids)) {
        $db->insertRegistrantInterests($registrant_id, $interest_ids);
    }
    // Redirigir con mensaje de éxito
    header("Location: index.php?success=1");
    exit;
} catch (Exception $e) {
    echo "<h2>Error al guardar:</h2><p>" . htmlspecialchars($e->getMessage()) . "</p><p><a href='index.php'>Volver</a></p>";
    exit;
}
