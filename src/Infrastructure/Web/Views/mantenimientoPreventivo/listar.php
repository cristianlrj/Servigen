<?php
headerAdmin($data);
?>
<div class="container-fluid content-inner mt-n5 py-0 pt-5">
   <div class="row mt-4">
      <div class="col-sm-12">
         <?php if (!empty($data['mensaje'])) echo $data['mensaje']; ?>
         <div class="card">
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title">Mantenimientos Preventivos</h4>
               </div>
              <div>
                <a href="<?= base_url() ?>/mantenimientoPreventivo/crear" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Programar Mantenimiento</a>
              </div>
            </div>
            <div class="card-body">
                  <table id="datatable" class="table table-sm w-100 responsive" data-toggle="data-table">
                     <thead>
                        <tr>
                            <th>ID</th>
                            <th>Equipo</th>
                            <th>Taller Asignado</th>
                            <th>Tipo de Mantenimiento</th>
                            <th>Fecha Programada</th>
                            <th>Estado</th>
                            <th>Usuario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['mantenimientos'])): ?>
                            <tr>
                                <td colspan="8" class="text-center">No hay mantenimientos preventivos para mostrar.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['mantenimientos'] as $mantenimiento): ?>
                                <?php
                                   $usuario = $data['getUsuarioUseCase']->ejecutar($mantenimiento->getIdUsuario());
                                   $taller = $data['getTallerUseCase']->ejecutar($mantenimiento->getIdTaller());

                                    // Lógica para el color del badge
                                    $estado = $mantenimiento->getEstado();
                                    $badgeClass = 'bg-secondary'; // Default
                                    if ($estado == 'Programado') $badgeClass = 'bg-primary';
                                    else if ($estado == 'En Proceso') $badgeClass = 'bg-info';
                                    else if ($estado == 'Completado') $badgeClass = 'bg-success';
                                    else if ($estado == 'Cancelado') $badgeClass = 'bg-danger';
                                ?>
                                <tr>
                                    <td><?= str_pad($mantenimiento->getId(), 5, '0', STR_PAD_LEFT) ?></td>
                                    <td><?= htmlspecialchars($mantenimiento->getNombreEquipo()) ?></td>
                                    <td><?= htmlspecialchars($taller->getNombreTaller() ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($mantenimiento->getTipoMantenimiento()) ?></td>
                                    <td><?= htmlspecialchars($mantenimiento->getFechaProgramada()) ?></td>
                                    <td><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($estado) ?></span></td>
                                    <td><?= htmlspecialchars($usuario->getNombre() ?? 'N/A') ?></td>
                                    <td>
                                        <div class="d-flex gap-1"> 
                                            <a href="#" class="btn btn-primary btn-sm" title="Actualizar Estado">
                                                <i class="fa-solid fa-arrows-rotate"></i>
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

<?php footerAdmin($data); ?>