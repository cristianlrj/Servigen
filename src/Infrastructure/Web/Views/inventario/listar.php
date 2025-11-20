<?php headerAdmin($data); ?>
<div class="container-fluid content-inner mt-n5 py-0 pt-5">
   <div class="row mt-4">
      <div class="col-sm-12">
        <?php if ($data['mensaje'] != '') echo $data['mensaje']; ?>
          <div class="card">
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title"><?= $data['title'] ?></h4>
               </div>
              <div>
                <a href="<?= base_url() ?>/inventario/crear" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Crear Artículo</a>
                <a id="generarReportePdf" href="<?= base_url() ?>/reporte/reporteInventarioPdf" target="_blank" class="btn btn-danger"><i class="fa-solid fa-file-pdf"></i> Generar reporte de almacén</a>
              </div>
            </div>
            <div class="card-body">
              <div class="row mb-3">
                <div class="col-md-4">
                  <label for="filtroTaller" class="form-label">Filtrar por Taller:</label>
                  <select class="form-select" id="filtroTaller">
 <option value="" data-taller-id="">Todos los Talleres</option>
                    <?php foreach ($data['talleres'] as $taller): ?>
 <option value="<?= htmlspecialchars($taller->getNombreTaller()) ?>" data-taller-id="<?= htmlspecialchars($taller->getId()) ?>">
 <?= htmlspecialchars($taller->getNombreTaller()) ?>
 </option>
                    <?php endforeach; ?>
                  </select>
              </div>
                <div class="col-md-4">
                  <label for="filtroTipo" class="form-label">Filtrar por Tipo:</label>
                  <select class="form-select" id="filtroTipo">
                    <option value="">Todos los Tipos</option>
                    <option value="Materia prima">Materia prima</option>
                    <option value="Herramienta">Herramienta</option>
                    <option value="Consumible">Consumible</option>
                  </select>
              </div>
            </div>
            <div class="card-body">
                  <table id="datatable" class="table table-sm w-100 responsive" data-toggle="data-table">
                     <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Marca</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Taller</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['inventario'])): ?>
                            <tr>
                                <td colspan="7" class="text-center">No hay artículos en el inventario.</td>
                            </tr>
                        <?php else: ?>
                            <?php 
                                $talleresMap = [];
                                foreach ($data['talleres'] as $taller) {
                                    $talleresMap[$taller->getId()] = $taller->getNombreTaller();
                                }
                            ?>
                            <?php foreach ($data['inventario'] as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item->getCodigo()) ?></td>
                                <td><?= htmlspecialchars($item->getNombre()) ?></td>
                                <td><?= htmlspecialchars($item->getMarca() ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars(ucfirst($item->getTipo())) ?></td>
                                <td><?= $item->getCantidad() ?></td>
                                <td><?= htmlspecialchars($talleresMap[$item->getIdTaller()] ?? 'Desconocido') ?></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="<?= base_url() ?>/inventario/editar/<?= $item->getId() ?>" class="btn btn-primary btn-sm" title="Editar"><i class="fa-solid fa-pencil"></i></a>
                                        <a href="<?= base_url() ?>/inventario/movimiento/<?= $item->getId() ?>" class="btn btn-info btn-sm" title="Gestionar Stock"><i class="fa-solid fa-right-left"></i></a>
                                        <a href="<?= base_url() ?>/inventario/eliminar/<?= $item->getId() ?>" onclick="return confirm('¿Está seguro de que desea desactivar este artículo?');" class="btn btn-danger btn-sm" title="Desactivar"><i class="fa-solid fa-trash-can"></i></a>
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
<script>
    $(document).ready(function() {
        var table = $('#datatable').DataTable();
 const generarReportePdfButton = document.getElementById('generarReportePdf');

    $('#filtroTaller').on('change', function() {
            var tallerSeleccionado = $(this).val();
            if (tallerSeleccionado) {
                table.column(5).search('^' + tallerSeleccionado + '$', true, false).draw();
            } else {
                table.column(5).search('').draw();
            }
             updatePdfLink();
        });
         $('#filtroTipo').on('change', function() {
            var tipoSeleccionado = $(this).val();
            if (tipoSeleccionado) {
                // Columna 3 es la de Tipo
                table.column(3).search('^' + tipoSeleccionado + '$', true, false).draw();
            } else {
                table.column(3).search('').draw();
            }
 updatePdfLink();
        });

 function updatePdfLink() {
 const selectedTallerName = $('#filtroTaller').val();
 const selectedTipo = $('#filtroTipo').val();

 // Obtener el ID del taller seleccionado si existe
 const selectedTallerId = $('#filtroTaller option:selected').data('taller-id');

 const tallerParam = selectedTallerId !== 'null' ? selectedTallerId : 'null';
 const tipoParam = selectedTipo ? encodeURIComponent(selectedTipo) : 'null';
 generarReportePdfButton.href = `<?= base_url() ?>/reporte/reporteInventarioPdf/${tallerParam}/${tipoParam}`;
 }
    });
</script>