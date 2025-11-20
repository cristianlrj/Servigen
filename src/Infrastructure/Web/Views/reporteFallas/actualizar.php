<?php
headerAdmin($data);
?>

<div class="container-fluid content-inner mt-n5 py-0 pt-5">
   <div class="row mt-4">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title"><?= $data['title'] ?></h4>
                 
               </div>
                <h5>umypf_n: <?= $data['falla']->getId() ?> </h5>
            </div>
            <div class="card-body">
               <form action="<?= base_url() ?>/reporteFallas/actualizarEstado" method="POST">
                  <input type="hidden" name="umypf_n" value="<?= $data['falla']->getId() ?>">
                  <div class="form-group">
                     <label for="descripcion" class="form-label">Descripción del Cambio:</label>
                     <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                  </div>
                  <div class="form-group">
                     <label for="estado" class="form-label">Nuevo Estado:</label>
                     <select class="form-select" id="estado" name="estado" required>
                        <?php $currentStatus = $data['falla']->getEstado(); ?>
                        <option value="" disabled selected>Seleccione un estado</option>
                        <?php if ($currentStatus == 'Pendiente'): ?>
                           <option value="Pendiente" selected>Pendiente</option>
                           <option value="En Proceso">En Proceso</option>
                           <option value="Cancelada">Cancelada</option>
 <?php elseif ($currentStatus == 'En Proceso'): ?>
                           <option value="En Proceso" selected>En Proceso</option>
                           <option value="Finalizada">Finalizada</option>
 <option value="Cancelada">Cancelada</option>
                        <?php else: // Finalizada o Cancelada ?>
                           <option value="<?= $currentStatus ?>" selected disabled><?= $currentStatus ?></option>
                        <?php endif; ?>
                     </select>
                  </div>
                  

 <?php if ($currentStatus == 'En Proceso'): ?>
 <div id="materialesForm" style="display: none; margin-top: 20px;">
 <h5>Materiales Utilizados (Solo Materia Prima y Consumibles)</h5>
 <div id="materialesContainer">
 <!-- Aquí se añadirán dinámicamente los campos de materiales -->
 </div>
 <button type="button" class="btn btn-info mt-2" id="addMaterial"><i class="fa-solid fa-plus"></i> Añadir Material</button>
 </div>
 <?php endif; ?>

 <button type="submit" class="btn btn-primary mt-2" <?= ($currentStatus == 'Finalizada' || $currentStatus == 'Cancelada') ? 'disabled' : '' ?>>Actualizar Reporte</button>
<a href="<?= base_url() ?>/reporteFallas/listar" class="btn btn-secondary mt-2">Volver</a>
                </form>
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
 const estadoSelect = document.getElementById('estado');
 const materialesForm = document.getElementById('materialesForm');
 const materialesContainer = document.getElementById('materialesContainer');
 const addMaterialButton = document.getElementById('addMaterial');
 let materialIndex = 0;

 estadoSelect.addEventListener('change', function() {
 if (this.value === 'Finalizada') {
 materialesForm.style.display = 'block';
 } else {
 materialesForm.style.display = 'none';
 materialesContainer.innerHTML = ''; // Limpiar materiales si se cambia de estado
 materialIndex = 0;
 }
 });

 addMaterialButton.addEventListener('click', function() {
 const newMaterialGroup = document.createElement('div');
 newMaterialGroup.classList.add('row', 'mb-2', 'material-group');
 newMaterialGroup.innerHTML = `
 <div class="col-md-6">
 <label for="material_${materialIndex}" class="form-label">Material:</label>
 <select class="form-select" name="materiales[${materialIndex}][id]" required>
 <option value="">Seleccione un material</option>
 <?php foreach ($data['materiales'] as $material) : ?>
 <option value="<?= $material->getId() ?>">
 <?= htmlspecialchars($material->getNombre()) ?> (Stock: <?= $material->getCantidad() ?>)
 </option>
 <?php endforeach; ?>
 </select>
 </div>
 <div class="col-md-4">
 <label for="cantidad_material_${materialIndex}" class="form-label">Cantidad:</label>
 <input type="number" class="form-control" name="materiales[${materialIndex}][cantidad]" min="1" required>
 </div>
 <div class="col-md-2 d-flex align-items-end">
 <button type="button" class="btn btn-danger remove-material"><i class="fa-solid fa-trash-can"></i></button>
 </div>
 `;
 materialesContainer.appendChild(newMaterialGroup);
 materialIndex++;

 // Añadir evento para eliminar material
 newMaterialGroup.querySelector('.remove-material').addEventListener('click', function() {
 newMaterialGroup.remove();
 });

 // Aquí deberías cargar las opciones de materiales (materia prima y consumibles) dinámicamente
 // Puedes hacer una llamada AJAX para obtenerlos o tenerlos ya en una variable JS
 });
 });
</script>