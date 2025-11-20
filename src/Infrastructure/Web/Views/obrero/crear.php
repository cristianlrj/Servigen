<?php headerAdmin($data); ?>
<div class="conatiner-fluid content-inner mt-n5 py-0 pt-5">
         <div class="row mt-4">
            <div class="col">
               <div id="alert"></div>
               <?php if ($data['mensaje'] != '') echo $data['mensaje']; ?>
                <div class="card">
                  <div class="card-header d-flex justify-content-between">
                     <div class="header-title">
                        <h4 class="card-title">Registrar nuevo obrero</h4>
                     </div>
                  </div> 
                  <div class="card-body">
                     <div class="new-user-info">
                        <form action="<?= base_url() ?>/obrero/registrar" method="post">
                           <input type="hidden" name="id" value="0">
                           <div class="row">
                              <div class="form-group col-md-6">
                                    <label class="form-label" for="cedula">Cédula:</label>
                                 <div class="input-group">
                                    <input type="text" name="cedula" maxlength="20" required class="form-control" id="cedula" placeholder="Cédula">
                                    <button class="btn btn-primary" type="button" onclick="buscarObreroPorApi()"><i class="fa-solid fa-search"></i></button>
                                 </div>
                              </div>
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="nombre">Nombres:</label>
                                 <input type="text" name="nombre" maxlength="50" required class="form-control" id="nombre" placeholder="Nombres" readonly>
                              </div>
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="apellido">Apellidos:</label>
                                 <input type="text" name="apellido" maxlength="50" required class="form-control" id="apellido" placeholder="Apellidos" readonly>
                              </div>
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="cargo">Ocupación:</label>
                                 <input type="text" name="cargo" maxlength="50" required class="form-control" id="cargo" placeholder="Ocupación" readonly>
                              </div>
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="taller">Habilidades:</label>
                                 <input type="text" name="habilidades" maxlength="255" class="form-control" id="habilidades" placeholder="Habilidades (opcional)">
                              </div>
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="taller">Taller:</label>
                                 <select class="form-select" id="taller" name="taller">
                                    <option value="">Seleccionar Taller</option>
                                    <?php foreach ($data['talleres'] as $taller) : ?>
                                       <option value="<?= $taller->getId() ?>"><?= $taller->getNombreTaller() ?></option>
                                    <?php endforeach; ?>
                                 </select>
                              </div>
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="area">Área:</label>
                                 <select class="form-select" id="area" name="area">
                                    <option value="">Seleccionar Área (opcional)</option>
                                    <?php foreach ($data['areas'] as $area) : ?>
                                       <option value="<?= htmlspecialchars($area->getId()) ?>"><?= htmlspecialchars($area->getNombre()) ?></option>
                                    <?php endforeach; ?>
                                 </select>
                              </div>
                              
                           <button type="submit" class="btn btn-primary" id="submitButton" disabled>Agregar obrero</button>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <script type="text/javascript">
         const submitButton = document.getElementById('submitButton');
         // Validación para campos de texto que solo deben contener letras
         function validateTextOnly(inputElement, fieldName) {
            inputElement.addEventListener('input', function() {
               const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/; // Permite letras, espacios y tildes
               if (!regex.test(this.value)) {
                  this.setCustomValidity(`El campo ${fieldName} solo debe contener letras.`);
               } else {
                  this.setCustomValidity('');
               }
               this.reportValidity();
            });
         }

         // Aplicar validación a los campos de nombre y apellido
         validateTextOnly(document.getElementById('nombre'), 'Nombres');
         validateTextOnly(document.getElementById('apellido'), 'Apellidos');
          validateTextOnly(document.getElementById('habilidades'), 'Habilidades');

         // Validación para el campo de cédula (solo números) y limpieza de campos relacionados
         document.getElementById('cedula').addEventListener('keyup', function() {
            const regex = /^[0-9]*$/; // Solo números
            if (!regex.test(this.value)) {
               this.setCustomValidity('La cédula solo debe contener números.');
            } else {
               this.setCustomValidity('');
            }
            this.reportValidity();
            // Limpiar campos si la cédula cambia después de una búsqueda exitosa
            document.getElementById('nombre').value = '';
            document.getElementById('apellido').value = '';
            document.getElementById('cargo').value = '';
            submitButton.disabled = true; // Deshabilitar el botón si la cédula cambia

         });

         // Función para buscar obrero por API
         function buscarObreroPorApi() {
            const cedulaInput = document.getElementById('cedula');
            const cedula = cedulaInput.value.trim();

            if (cedula === '') {
               return; // No buscar si la cédula está vacía
            }

            // Validar que la cédula solo contenga números antes de enviar la solicitud
            if (!/^[0-9]*$/.test(cedula)) {
               return;
            }

            const formData = new FormData();
            formData.append('cedula', cedula);

            fetch('<?= base_url() ?>/obrero/buscarPorApi', {
               method: 'POST',
               body: formData
            })
            .then(response => response.json())
            .then(data => {
               if (data.status === 'success') {
                  // Llenar los campos con los datos obtenidos de la API
                  const fullName = data.obrero.nombre.split(' ');
                  document.getElementById('nombre').value = fullName[0] || '';
                  document.getElementById('apellido').value = fullName.slice(1).join(' ') || '';
                  document.getElementById('cargo').value = data.obrero.cargo || '';
                  
                  if (data.obrero.cargo.includes("Obrero")) {
                     submitButton.disabled = false; // Habilitar el botón si se encuentra el obrero y es OBRERO
                     showAlert('Obrero encontrado y verificado.', 'success');
                  } else {
                     showAlert('La persona encontrada no tiene el cargo de "Obrero". No se puede registrar.', 'warning');
                  }
               } else {
                  showAlert(data.message || 'Obrero no encontrado.', 'danger');
                  // Opcional: limpiar campos si no se encontró
                  document.getElementById('nombre').value = '';
                  document.getElementById('apellido').value = '';
                  document.getElementById('cargo').value = '';
                  submitButton.disabled = true; // Deshabilitar el botón si no se encuentra el obrero
               }
            })
            .catch(error => {
               console.error('Error al buscar obrero por API:', error);
               showAlert('Hubo un error al conectar la API.', 'danger');
            });
         }

         function showAlert(message, type) {
            const alertDiv = document.getElementById('alert');
            alertDiv.innerHTML = `
               <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                  ${message}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
               </div>
            `;
         }

      </script>

<?php footerAdmin($data); ?>