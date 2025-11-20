<?php headerAdmin($data); ?>
<div class="container-fluid content-inner mt-n5 py-0 pt-5">
   <div class="row mt-4">
      <div class="col-sm-12">
        <?php if (!empty($data['mensaje'])) echo $data['mensaje']; ?>
          <div class="card">
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title"><?= $data['title'] ?></h4>
               </div>
              <div>
                <a href="<?= base_url() ?>/preRequisicion/crear" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Crear Pre-Requisición</a>
                <a id="generarReportePdf" href="<?= base_url() ?>/reporte/reportePreRequisicionPdf" target="_blank" class="btn btn-danger"><i class="fa-solid fa-file-pdf"></i> Generar reporte de Pre-Requisiciones</a>
              </div>
            </div>
            <div class="card-body">
              <div class="row mb-3">
                <div class="col-md-4">
                  <label for="filtroTaller" class="form-label">Filtrar por Taller:</label>
                  <select class="form-select" id="filtroTaller">
                     <option value="">Todos los Talleres</option>
                    <?php foreach ($data['talleres'] as $taller): ?>
                     <option value="<?= htmlspecialchars($taller->getNombreTaller()) ?>">
                        <?= htmlspecialchars($taller->getNombreTaller()) ?>
                     </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-4">
                  <label for="filtroEstado" class="form-label">Filtrar por Estado:</label>
                  <select class="form-select" id="filtroEstado">
                    <option value="">Todos los Estados</option>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Aprobada">Aprobada</option>
                    <option value="Rechazada">Rechazada</option>
                  </select>
                </div>
              </div>
              <div class="table-responsive">
                  <table id="datatable" class="table table-striped" data-toggle="data-table">
                     <thead>
                        <tr>
                            <th>ID Pre-Req.</th>
                            <th># Reporte Falla</th>
                            <th>Fecha Creación</th>
                            <th>Estado</th>
                            <th>Taller</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $talleresMap = [];
                            foreach ($data['talleres'] as $taller) {
                                $talleresMap[$taller->getId()] = $taller->getNombreTaller();
                            }
                        ?>
                        <?php foreach ($data['preRequisiciones'] as $pre): ?>
                        <tr>
                            <td><?= htmlspecialchars($pre->getId()) ?></td>
                            <td><?= htmlspecialchars($pre->getUmypfN()) ?></td>
                            <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($pre->getFechaCreacion()))) ?></td>
                            <td><?= htmlspecialchars($pre->getEstado()) ?></td>
                            <td><?= htmlspecialchars($talleresMap[$pre->getIdTaller()] ?? 'Desconocido') ?></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="<?= base_url() ?>/preRequisicion/ver/<?= $pre->getId() ?>" class="btn btn-info btn-sm" title="Ver Detalles"><i class="fa-solid fa-eye"></i></a>
                                    <a href="<?= base_url() ?>/reporteFallas/editar/<?= $pre->getUmypfN() ?>" class="btn btn-secondary btn-sm" title="Ir a Reporte de Falla"><i class="fa-solid fa-bullhorn"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                  </table>
              </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php footerAdmin($data); ?>