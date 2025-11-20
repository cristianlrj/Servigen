<div class="report-container">
  <?php
    $usuario = $getUsuarioUseCase->ejecutar($reporte->getUsuarioId());
 $historial = $reporte->getHistorial();
    $taller = $getTallerUseCase->ejecutar($reporte->getIdTaller());
  ?>

  <img src="../../../public/assets/images/cintillo.png" class="cintillo">

  <div class="section">
    <h3>Reporte de Culminación de Falla</h3>
    <table>
      <tr>
        <th>UMYPF-N°:</th>
        <td><?= str_pad($reporte->getId(), 5, '0', STR_PAD_LEFT) ?></td>
      </tr>
      <tr>
        <th>Unidad Solicitante:</th>
        <td><?= htmlspecialchars($reporte->getUnidadSolicitante()) ?></td>
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
        <th>Descripción</th>
        <td><?= htmlspecialchars($reporte->getDescripcion()) ?></td>
      </tr>
      <tr>
        <th>Fecha de Inicio:</th>
        <td><?= date('d-m-Y H:i:s', strtotime($reporte->getFechaCreacion())) ?></td>
      </tr>
      <?php if (!empty($historial)): ?>
      <tr>
        <th>Fecha de Culminación:</th>
        <td><?= date('d-m-Y H:i:s', strtotime(end($historial)->getFecha())) ?></td>
      </tr>
      <?php endif; ?>
    </table>
  </div>
        <br><br>
  <div class="section">
    <h3>Formulario de Satisfacción</h3>
    <p>Por favor, califique su nivel de satisfacción con el servicio prestado:</p>
    <div class="satisfaction-form">
      <div class="rating-group">
        <label>
          <input type="radio" name="satisfaction" value="excelente" disabled> Excelente
        </label>
        <label>
          <input type="radio" name="satisfaction" value="bueno" disabled> Bueno
        </label>
        <label>
          <input type="radio" name="satisfaction" value="regular" disabled> Regular
        </label>
        <label>
          <input type="radio" name="satisfaction" value="malo" disabled> Malo
        </label>
      </div>
      <br>
      <div class="comments-group">
        <label for="comments">Comentarios adicionales:</label>
        <textarea id="comments" name="comments" rows="3" disabled></textarea>
      </div>
    </div>
  </div>

 <center>
     <div class="section signature-section">
    <h3>Firma de Conformidad</h3>
    <div class="signature-box">
    </div>
  </div>
 </center>

</div>