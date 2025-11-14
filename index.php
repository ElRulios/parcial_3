<?php
// index.php
require_once 'DB.php';
$db = DB::getInstance();

// Obtener países e intereses desde la DB:
try {
    $countries = $db->getCountries();
    $interests = $db->getInterests();
} catch (Exception $e) {
    $countries = [];
    $interests = [];
    // Si aún no existe la DB, puedes mostrar opciones por defecto
    $countries = [
        ['id'=>1,'name'=>'Panama'],
        ['id'=>2,'name'=>'Costa Rica']
    ];
    $interests = [
        ['id'=>1,'name'=>'Frontend (React/Vue)'],
        ['id'=>2,'name'=>'Backend (Node.js/PHP)']
    ];
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Formulario de Inscripción - iTECH</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h1>Formulario de Inscripción - Evento iTECH</h1>
    <p>Completa tus datos. Los campos con * son obligatorios.</p>

    <?php if(!empty($_GET['success'])): ?>
      <p class="success">Registro guardado correctamente.</p>
    <?php endif; ?>

    <form action="process.php" method="post" novalidate>
      <div class="form-grid">
        <div class="form-group">
          <label for="first_name">Nombre *</label>
          <input id="first_name" name="first_name" type="text" required>
        </div>

        <div class="form-group">
          <label for="last_name">Apellido *</label>
          <input id="last_name" name="last_name" type="text" required>
        </div>

        <div class="form-group">
          <label for="age">Edad *</label>
          <input id="age" name="age" type="number" min="0" max="120" required>
        </div>

        <div class="form-group">
          <label for="sexo">Sexo *</label>
          <select id="sexo" name="sexo" required>
            <option value="">-- Seleccione --</option>
            <option value="M">Masculino</option>
            <option value="F">Femenino</option>
            <option value="O">Otro</option>
          </select>
        </div>

        <div class="form-group">
          <label for="country_id">País de Residencia *</label>
          <select id="country_id" name="country_id" required>
            <option value="">-- Seleccione país --</option>
            <?php foreach($countries as $c): ?>
              <option value="<?=htmlspecialchars($c['id'])?>"><?=htmlspecialchars($c['name'])?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="nationality">Nacionalidad *</label>
          <input id="nationality" name="nationality" type="text" required>
        </div>

        <div class="form-group" style="grid-column:1 / -1">
          <label>Tema tecnológico que le gustaría aprender (marque todas las que apliquen)</label>
          <div class="checkbox-group">
            <?php foreach($interests as $i): ?>
              <label><input type="checkbox" name="interests[]" value="<?=htmlspecialchars($i['id'])?>"> <?=htmlspecialchars($i['name'])?></label>
            <?php endforeach; ?>
            <!-- Si no hay intereses en DB, muestra ejemplos -->
          </div>
        </div>

        <div class="form-group" style="grid-column:1 / -1">
          <label for="observations">Observaciones o Consulta sobre el evento</label>
          <textarea id="observations" name="observations"></textarea>
        </div>

        <div class="form-group">
          <label for="email">Correo</label>
          <input id="email" name="email" type="email" >
        </div>

        <div class="form-group">
          <label for="phone">Celular</label>
          <input id="phone" name="phone" type="text" >
        </div>

        <div class="form-group">
            <label for="fecha">Fecha del formulario:</label>
            <input id="date" type="date" id="fecha" name="fecha" required>
        </div>

      </div>

      <div class="controls">
        <button class="btn" type="submit">Enviar inscripción</button>
        <a class="btn secondary" href="report.php" style="text-decoration:none;display:inline-block">Ver reporte</a>
      </div>

      <div class="footer">
        <div>© <?=date('Y')?> iTECH. All rights reserved.</div>
      </div>
    </form>
  </div>
</body>
</html>
