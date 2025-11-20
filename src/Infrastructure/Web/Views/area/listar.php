<?php headerAdmin($data); ?>

<!-- Modal -->
<div class="modal fade" id="modalFormulario" tabindex="-1" aria-labelledby="modalFormularioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalFormularioLabel">Crear Nueva Área</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formArea" action="<?= base_url() ?>/area/registrar" method="post">
        <input type="hidden" name="id" id="id" value="">
        <div class="modal-body">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Área</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

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
                <button type="button" class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> Crear Área</button>
              </div>
            </div>
            <div class="card-body">
                  <table id="datatable" class="table table-sm w-100 responsive" data-toggle="data-table">
                     <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['areas'] as $area): ?>
                        <tr>
                            <td><?= $area->getId() ?></td>
                            <td><?= htmlspecialchars($area->getNombre()) ?></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button onclick="editModal(this)" data-id="<?= $area->getId() ?>" data-nombre="<?= htmlspecialchars($area->getNombre()) ?>" class="btn btn-primary btn-sm" title="Editar"><i class="fa-solid fa-pencil"></i></button>
                                    <a href="<?= base_url() ?>/area/eliminar/<?= $area->getId() ?>" onclick="return confirm('¿Está seguro de que desea desactivar esta área?');" class="btn btn-danger btn-sm" title="Desactivar"><i class="fa-solid fa-trash-can"></i></a>
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


<?php footerAdmin($data); ?>

<script>
    const modal = new bootstrap.Modal(document.getElementById('modalFormulario'));
    const modalLabel = document.getElementById('modalFormularioLabel');
    const form = document.getElementById('formArea');
    const inputId = document.getElementById('id');
    const inputNombre = document.getElementById('nombre');

    function openModal() {
        modalLabel.textContent = 'Crear Nueva Área';
        form.action = '<?= base_url() ?>/area/registrar';
        inputId.value = '';
        inputNombre.value = '';
        modal.show();
    }

    function editModal(button) {
        modalLabel.textContent = 'Actualizar Área';
        form.action = '<?= base_url() ?>/area/actualizar';
        const id = button.getAttribute('data-id');
        const nombre = button.getAttribute('data-nombre');
        inputId.value = id;
        inputNombre.value = nombre;
        modal.show();
    }
</script>