<div class="report-container">

  <img src="<?= base_url() ?>/public/assets/images/cintillo.jpeg" class="cintillo">

  <h4 class="report-title"><?= $titulo ?></h4>

  <table id="fallas">
    <thead>
      <tr>
        <th>UMYPF-N°</th>
        <th>Fecha</th>
        <th>Unidad Solicitante</th> 
        <th>Contacto</th>
        <th>Descripción de la Falla</th>
        <th>Taller</th>
        <th>Usuario</th>
        <th>Estado</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($reportes as $reporte): 
        
         

        $usuario = $getUsuarioUseCase->ejecutar($reporte->getUsuarioId());
        $taller = $getTallerUseCase->ejecutar($reporte->getIdTaller());
        $departamento = $getDepartamentoUseCase->ejecutar($reporte->getUnidadSolicitante());


        $estado = $reporte->getEstado();
        $claseEstado = 'status-default'; // Clase por defecto
        
        // Convertimos a minúscula y quitamos espacios para el nombre de la clase
        $estadoNormalizado = str_replace(' ', '-', strtolower($estado));

        switch ($estadoNormalizado) {
          case 'pendiente':
            $claseEstado = 'status-pendiente';
            break;
          case 'en-proceso':
            $claseEstado = 'status-en-proceso';
            break;
          case 'completado':
            $claseEstado = 'status-finalizado';
            break;
          case 'cancelado':
            $claseEstado = 'status-cancelado';
            break;
        } ?>
        <tr>
          <td><?php echo $reporte->getId(); ?></td>
          <td><?php echo date('d-m-Y', strtotime($reporte->getFechaCreacion())); ?></td>
          <td><?php echo $departamento->getNombre(); ?></td>
          <td><?php echo $reporte->getPersonaContacto(); ?></td>
          <td><?php echo $reporte->getDescripcion(); ?></td>
          <td><?php echo $taller->getNombreTaller(); ?></td>
          <td><?php echo $usuario->getNombre(); ?></td>
          
          <td>
            <span class="status-tag <?php echo $claseEstado; ?>">
              <?php echo $estado; ?>
            </span>
          </td>

        </tr>
      <?php 
    endforeach; 
    
    ?>
    </tbody>
  </table>

</div>