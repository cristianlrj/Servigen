<?php headerAdmin($data); ?>

<div class="container-fluid content-inner mt-n5 py-0 pt-5">
   <div class="row mt-4">
      <div class="col">
         <div id="home"></div>
        <?php if ($data['mensaje'] != '') echo $data['mensaje']; ?>
         <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
               <h4 class="card-title mb-0">Divisiones de Área</h4>
               
                  <div>
                     <a href="<?= base_url() ?>/obrero/crear" class="btn btn-primary"><i class="fa-solid fa-plus"></i>Registrar Obrero</a>

                     <a id="generarReportePdf" href="<?= base_url() ?>/reporte/reporteObrerosPdf" target="_blank" class="btn btn-danger"><i class="fa-solid fa-file-pdf"></i> Generar reporte de obreros</a>
                  </div>

               </div>

            <div class="card-body">
               <!-- Tabs -->
               <div class="form-group mb-3 w-50">
                  <label for="tallerFilter">Filtrar por Taller:</label>
                  <select class="form-select" id="tallerFilter">
                     <option value="">Todos</option>
                     <?php foreach ($data['talleres'] as $taller) : ?>
                        <option value="<?= $taller->getId() ?>"><?= $taller->getNombreTaller() ?></option>
                     <?php endforeach; ?>
                  </select>
               </div>
               
               <!-- Mensaje de error o éxito -->
               <?php if (isset($data['mensaje']) && $data['mensaje'] != '') : ?>
                  <div class="alert alert-info"><?= $data['mensaje'] ?></div>
               <?php endif; ?>


               <!-- Tabla -->
               <div class="table-responsive">
                  <table class="table table-striped" id="obreros" data-toggle="data-table">
                     <thead class="table-light">
                        <tr>
                           <th>Cédula</th>
                           <th>Nombres</th>
                           <th>Apellidos</th>
                           <th>Taller</th>
                           <th>Ocupación</th>
                           <th>Habilidades</th>
                           <th>Ubicación Área</th>
                           <th>Acciones</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach ($data['obreros'] as $obrero) : ?>
                           <tr data-taller="<?= $obrero->taller ?>">
                              <td><?= $obrero->cedula ?></td>
                              <td><?= $obrero->nombre ?></td>
                              <td><?= $obrero->apellido ?></td>
                              <td><?= isset($data['talleresMap'][$obrero->taller]) ? $data['talleresMap'][$obrero->taller] : 'No asignado' ?></td>
                              <td><?= $obrero->cargo ?></td>
                              <td><?= $obrero->habilidades ?></td>
                              <td>
                                 <div class="d-flex input-group align-items-center" data-obrero-id="<?= $obrero->id ?>">
                                    <select class="form-select area-select flex-grow-1">
                                       <option value="">Seleccionar Área</option>
                                       <?php foreach ($data['areas'] as $area) : ?>
                                          <option value="<?= $area->getId() ?>" <?= ($area->getId() == $obrero->area) ? 'selected' : '' ?>>
                                             <?= htmlspecialchars($area->getNombre()) ?>
                                          </option>
                                       <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-icon btn-primary btn-update-area ms-2" type="button" title="Actualizar Área">
                                       <i class="fa-solid fa-rotate"></i>
                                    </button>
                                 </div>
                              </td>
                              <td>
                                 <div class="d-flex">
                                    <a href="<?= base_url() ?>/obrero/editar/<?= $obrero->id ?>" class="btn btn-icon btn-primary me-1" title="Editar">
                                    <i class="fa-solid fa-pencil"></i>
                                 </a>
                                 <a href="#" class="btn btn-icon btn-danger" title="Eliminar">
                                    <i class="fa-solid fa-trash-can"></i>
                                 </a>
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

<script>
   // Filtro por pestañas
   const tallerFilter = document.getElementById('tallerFilter');
   const rows = document.querySelectorAll('#obreros tbody tr');
   const generarReportePdfButton = document.getElementById('generarReportePdf');

   tallerFilter.addEventListener('change', function () {
      const selectedId = this.value;
      const selectedText = this.options[this.selectedIndex].text;

         rows.forEach(row => {
            const tallerId = row.getAttribute('data-taller');
            // Convertir a número para comparación estricta si es necesario
            // o asegurar que data-taller siempre sea string o number consistente
            row.style.display = (selectedId == 0 || selectedId == tallerId) ? '' : 'none';
         });

                 // Actualizar el enlace del PDF
        const tallerParam = selectedId ? selectedId : 'null';
        generarReportePdfButton.href = `<?= base_url() ?>/reporte/reporteObrerosPdf/${tallerParam}`;
      });

   // Lógica para actualizar el área
   document.querySelectorAll('.btn-update-area').forEach(button => {
      button.addEventListener('click', function() {
         const inputGroup = this.closest('.input-group'); // Obtener el input-group padre
         const input = inputGroup.querySelector('.area-select'); // Seleccionar el <select> dentro del input-group
         const obreroId = inputGroup.dataset.obreroId;
         const newArea = input.value;

         console.log(`Actualizar obrero ${obreroId} con nueva área: ${newArea}`);

         // Enviar la actualización al servidor usando fetch API
         const formData = new FormData();
         formData.append('id', obreroId);
         formData.append('area', newArea);

         fetch('<?= base_url() ?>/obrero/actualizarArea', { // Asegúrate de que esta URL sea la correcta para tu controlador
            method: 'POST',
            body: formData,
         })
         .then(response => response.json())
         .then(data => {
            console.log('Respuesta del servidor:', data);
            // Aquí puedes manejar la respuesta, por ejemplo, mostrar un mensaje de éxito o error
            if (data.status === 'success') {
               document.getElementById('home').innerHTML = data.message;
            } else {
               document.getElementById('home').innerHTML = data.message;
            }
         })
         .catch(error => {
            console.error('Error en la solicitud:', error);
            alert('Hubo un problema al conectar con el servidor.');
         });
      });
   });
</script>

<?php footerAdmin($data); ?>
