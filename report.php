<?php
// report.php
require_once 'DB.php';
$db = DB::getInstance();
$rows = $db->getReport();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Reporte de Inscripciones - iTECH</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h1>Reporte de Inscripciones</h1>
    <p>Listado de inscripciones guardadas en la base de datos.</p>

    <?php if(empty($rows)): ?>
      <p>No se encontraron registros.</p>
    <?php else: ?>
      <table style="width:100%;border-collapse:collapse">
        <thead style="text-align:left">
          <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Edad</th>
            <th>Sexo</th>
            <th>País</th>
            <th>Nacionalidad</th>
            <th>Intereses</th>
            <th>Correo</th>
            <th>Celular</th>
            <th>Fecha</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($rows as $r): ?>
          <tr style="border-top:1px solid #eee">
            <td><?=htmlspecialchars($r['id'])?></td>
            <td><?=htmlspecialchars($r['first_name'] . ' ' . $r['last_name'])?></td>
            <td><?=htmlspecialchars($r['age'])?></td>
            <td><?=htmlspecialchars($r['sexo'])?></td>
            <td><?=htmlspecialchars($r['country'])?></td>
            <td><?=htmlspecialchars($r['nationality'])?></td>
            <td><?=htmlspecialchars($r['interests'] ?? '')?></td>
            <td><?=htmlspecialchars($r['email'])?></td>
            <td><?=htmlspecialchars($r['phone'])?></td>
            <td><?=htmlspecialchars($r['form_date'])?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <div style="margin-top:16px">
      <a class="btn" href="index.php">Nuevo registro</a>
    </div>

    <div class="footer">
      <div>© <?=date('Y')?> iTECH. All rights reserved. Contacto: info@itech.example</div>
      <div>Reporte generado: <?=date('Y-m-d H:i:s')?></div>
    </div>
  </div>
</body>
</html>
