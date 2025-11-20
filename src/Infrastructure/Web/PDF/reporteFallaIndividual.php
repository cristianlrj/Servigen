<div class="report-container">
  <?php
    $usuario = $getUsuarioUseCase->ejecutar($reporte->getUsuarioId());
    $taller = $getTallerUseCase->ejecutar($reporte->getIdTaller());
    $departamento = $getDepartamentoUseCase->ejecutar($reporte->getUnidadSolicitante());
  
  ?>

  <img src="<?= base_url() ?>/public/assets/images/cintillo.jpeg" class="cintillo">

  <div class="section">
    <h3>Detalles del Reporte</h3>
    <table>
      <tr>
        <th>UMYPF-N°:</th>
        <td><?= str_pad($reporte->getId(), 5, '0', STR_PAD_LEFT) ?></td>
      </tr>
      <tr>
        <th>Fecha de Creación:</th>
        <td><?= date('d-m-Y H:i:s', strtotime($reporte->getFechaCreacion())) ?></td>
      </tr>
      <tr>
        <th>Unidad Solicitante:</th>
        <td><?= htmlspecialchars($departamento->getNombre()) ?></td>
      </tr>
      <tr>
        <th>Persona de Contacto:</th>
        <td><?= htmlspecialchars($reporte->getPersonaContacto()) ?></td>
      </tr>
      <tr>
        <th>Taller Asignado:</th>
        <td><?= htmlspecialchars($taller->getNombreTaller() ?? 'N/A') ?></td>
      </tr>
      <tr>
        <th>Reportado por:</th>
        <td><?= htmlspecialchars($usuario->getNombre() . ' ' . $usuario->getApellido() ?? 'N/A') ?></td>
      </tr>
      <tr>
        <th>Estado Actual:</th>
        <td>
          <?php
            $estado = $reporte->getEstado();
            $claseEstado = 'status-default';
            $estadoNormalizado = str_replace(' ', '-', strtolower($estado));

            switch ($estadoNormalizado) {
              case 'pendiente':
                $claseEstado = 'status-pendiente';
                break;
              case 'en-proceso':
                $claseEstado = 'status-en-proceso';
                break;
              case 'finalizada':
                $claseEstado = 'status-finalizado';
                break;
              case 'cancelada':
                $claseEstado = 'status-cancelado';
                break;
            }
          ?>
          <span class="status-tag <?= $claseEstado ?>">
            <?= htmlspecialchars($estado) ?>
          </span>
        </td>
      </tr>
    </table>
  </div>

  <div class="section">
    <h3>Historial de la Falla</h3>
    <table id="historial" style="width: 100%;">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Descripción</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reporte->getHistorial() as $index => $entrada): ?>
          <tr class="<?= ($index % 2 == 0) ? 'even-row' : 'odd-row' ?>">
            <td><?= date('d-m-Y H:i:s', strtotime($entrada->getFecha())) ?></td>
            <td><?= htmlspecialchars($entrada->getDescripcion()) ?></td>
            <td><?= htmlspecialchars($entrada->getEstado()) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php if ($reporte->getSatisfaccion() !== null && $reporte->getSatisfaccion() !== 0): ?>
  <div class="section">
    <h3>Satisfacción del Cliente</h3>
    <table>
      <tr>
        <th>Nivel de Satisfacción:</th>
        <td>
          <?php
            $satisfaccion = $reporte->getSatisfaccion();
            switch ($satisfaccion) {
              case 5:
                echo '5 - Excelente';
                break;
              case 4:
                echo '4 - Muy Bueno';
                break;
              case 3:
                echo '3 - Bueno';
                break;
              case 2:
                echo '2 - Regular';
                break;
              case 1:
                echo '1 - Malo';
                break;
              default:
                echo 'N/A';
                break;
            }
          ?>
        </td>
      </tr>
      <tr>
        <th>Comentarios:</th>
        <td><?= htmlspecialchars($reporte->getComentariosSatisfaccion() ?? 'N/A') ?></td>
      </tr>
    </table>
  </div>
  <?php endif; ?>

  

</div>