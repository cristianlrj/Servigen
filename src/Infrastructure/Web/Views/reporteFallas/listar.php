<?php
headerAdmin($data);
?>
<div class="container-fluid content-inner mt-n5 py-0 pt-5">
   <div class="row mt-4">
      <div class="col-sm-12">
         <?php if ($data['mensaje'] != '') echo $data['mensaje']; ?>
         <div class="card">
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title">Reportes de Fallas</h4>
               </div>
              <div>
                <a href="<?= base_url() ?>/reporteFallas/crear" class="btn btn-primary"><i class="fa-solid fa-plus"></i>Crear reporte</a>

               <a id="generarReportePdf" href="<?= base_url() ?>/reporte/reporteFallasPdf" target="_blank" class="btn btn-danger"><i class="fa-solid fa-file-pdf"></i> Generar reporte de fallas</a>
              </div>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2 mb-3">
                <div class="form-group">
                    <label for="filterTaller">Filtrar por Taller:</label>
                    <select class="form-select" id="filterTaller">
                    <option value="">Todos</option>
                        <?php foreach ($data['talleres'] as $taller): ?>
                    <option value="<?= htmlspecialchars($taller->getNombreTaller()) ?>" data-taller-id="<?= htmlspecialchars($taller->getId()) ?>">
                        <?= htmlspecialchars($taller->getNombreTaller()) ?>
                    </option>
                    <?php endforeach; ?>
                    </select>
                </div>
                    <div class="form-group">
                    <label for="filterEstado">Filtrar por Estado:</label>
                    <select class="form-select" id="filterEstado">
                        <option value="">Todos los Estados</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="En Proceso">En Proceso</option>
                        <option value="Finalizada">Finalizada</option>
                        <option value="Cancelada">Cancelada</option>
                    </select>
                </div>
                </div>
                  <table id="datatable" class="table table-sm w-100 responsive" data-toggle="data-table">
                     <thead>
                    <tr>
                        <th>UMYPF-N°</th>
                        <th>Unidad Solicitante</th>
                        <th>Persona de Contacto</th>
                        <th>Descripción de la Falla</th>
                        <th>Taller</th>
                        <th>Usuario</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

    <?php if (empty($data['fallas'])): ?>
        <tr>
            <td colspan="8" class="text-center">No hay reportes de fallas para mostrar.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($data['fallas'] as $falla): ?>
            <?php
               $usuario = $getUsuarioUseCase->ejecutar($falla->getUsuarioId());
               $taller = $getTallerUseCase->ejecutar($falla->getIdTaller());
               $departamento = $getDepartamentoUseCase->ejecutar($falla->getUnidadSolicitante());


                // Lógica para el color del badge
                $estado = $falla->getEstado();
                $badgeClass = 'bg-secondary'; // Default
                if ($estado == 'Pendiente') $badgeClass = 'bg-warning';
                else if ($estado == 'En Proceso') $badgeClass = 'bg-info';
                else if ($estado == 'Finalizada') $badgeClass = 'bg-success';
                else if ($estado == 'Cancelada') $badgeClass = 'bg-danger';
            ?>
            <tr>
                <td><?= str_pad($falla->getId(), 5, '0', STR_PAD_LEFT) ?></td>
                <td><?= htmlspecialchars($departamento->getNombre()) ?></td>
                <td><?= htmlspecialchars($falla->getPersonaContacto()) ?></td>
                <td><?= htmlspecialchars($falla->getDescripcion()) ?></td>
                <td><?= htmlspecialchars($taller->getNombreTaller() ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($usuario->getNombre() ?? 'N/A') ?></td>
                <td><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($estado) ?></span></td>
                
                <td>
                    <div class="d-flex gap-1"> 

                        <?php if ($estado == 'Finalizada'): ?>
                            <!-- Enlace directo para enviar correo. Deshabilitado si no hay email o la encuesta ya se llenó. -->
                            <a href="<?= base_url() ?>/reporteFallas/enviarCorreoSatisfaccion/<?= $falla->getId() ?>" 
                               class="btn btn-warning btn-sm <?= ($falla->getEmailContacto() === null || ($falla->getSatisfaccion() !== null && $falla->getSatisfaccion() !== 0)) ? 'disabled' : '' ?>" 
                               title="Enviar Encuesta de Satisfacción">
                                <i class="fa-solid fa-envelope"></i>
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url() ?>/reporteFallas/editar/<?= $falla->getId() ?>" class="btn btn-primary btn-sm <?= ($estado == 'Cancelada') ? 'disabled' : '' ?>" title="Actualizar Estado">
                                <i class="fa-solid fa-arrows-rotate"></i>
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?= base_url() ?>/reporte/reporteCulminacionPdf/<?= $falla->getId() ?>" target="_blank" class="btn btn-success btn-sm <?= ($estado == 'Finalizada' && $falla->getSatisfaccion() === 0) ? '' : 'disabled' ?>" title="Reporte de Culminación">
                            <i class="fa-solid fa-file-circle-check"></i>
                         </a>
                        
                        <a href="<?= base_url() ?>/reporte/reporteFallasIndividualPdf/<?= $falla->getId() ?>" target="_blank" class="btn btn-secondary btn-sm" title="Ver Reporte">
                            <i class="fa-solid fa-file-lines"></i>
                        </a>

                    </div>
                    
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</tbody>
                  </table>
            </div>
         </div>
      </div>
   </div>
</div>

<?php
footerAdmin($data);
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dataTable = $('#datatable').DataTable();
    const filterTaller = document.getElementById('filterTaller');
    const filterEstado = document.getElementById('filterEstado');
    const generarReportePdfButton = document.getElementById('generarReportePdf');

    function applyFilters() {
        const selectedTallerName = filterTaller.value;
        const selectedTallerId = filterTaller.options[filterTaller.selectedIndex].dataset.tallerId;
        const selectedEstado = filterEstado.value;

        // Filtrar por taller (columna 4)
        dataTable.column(4).search(selectedTallerName).draw();

        // Filtrar por estado (columna 6)
        dataTable.column(6).search(selectedEstado).draw();

        // Actualizar el enlace del PDF
        const tallerParam = selectedTallerId ? selectedTallerId : 'null';
        const estadoParam = selectedEstado ? selectedEstado : 'null';
        generarReportePdfButton.href = `<?= base_url() ?>/reporte/reporteFallasPdf/${tallerParam}/${estadoParam}`;

    }

    filterTaller.addEventListener('change', applyFilters);
    filterEstado.addEventListener('change', applyFilters);
});
</script>
