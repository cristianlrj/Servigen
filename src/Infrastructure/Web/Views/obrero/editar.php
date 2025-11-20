<?php headerAdmin($data); ?>
<div class="conatiner-fluid content-inner mt-n5 py-0 pt-5">
         <div class="row mt-4">
            <div class="col">
               <?php if ($data['mensaje'] != '') echo $data['mensaje']; ?>
               <div class="card">
                  <div class="card-header d-flex justify-content-between">
                     <div class="header-title">
                        <h4 class="card-title">Editar obrero</h4>
                     </div>
                  </div> 
                  <div class="card-body">
                     <div class="new-user-info">
                        <form action="<?= base_url() ?>/obrero/actualizar" method="post">
                           <input type="hidden" name="id" value="<?= $obrero->getId() ?>">
                           <div class="row">
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="nombre">Nombres:</label>
                                 <input type="text" name="nombre" maxlength="50" value="<?= htmlspecialchars($obrero->getNombre()) ?>" required class="form-control" id="nombre" placeholder="Nombres" readonly>
                              </div>
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="cedula">Cédula:</label>
                                 <input type="text" name="cedula" maxlength="20" value="<?= htmlspecialchars($obrero->getCedula()) ?>" required class="form-control" id="cedula" placeholder="Cédula" readonly>
                              </div>
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="apellido">Apellidos:</label>
                                 <input type="text" name="apellido" maxlength="50" value="<?= htmlspecialchars($obrero->getApellido()) ?>" required class="form-control" id="apellido" placeholder="Apellidos" readonly>
                              </div>
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="cargo">Ocupación:</label>
                                 <input type="text" name="cargo" maxlength="50" value="<?= htmlspecialchars($obrero->getCargo()) ?>" required class="form-control" id="cargo" placeholder="Ocupación" readonly>
                              </div>
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="habilidades">Habilidades:</label>
                                 <input type="text" name="habilidades" maxlength="255" value="<?= htmlspecialchars($obrero->getHabilidades()) ?>" class="form-control" id="habilidades" placeholder="Habilidades (opcional)">
                              </div>
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="taller">Taller:</label>
                                 <select class="form-select" id="taller" name="taller" required>
                                    <option value="">Seleccionar Taller</option>
                                    <?php foreach ($data['talleres'] as $taller) : ?>
                                       <option value="<?= $taller->getId() ?>" <?= ($taller->getId() == $obrero->getTaller()) ? 'selected' : '' ?>>
                                          <?= htmlspecialchars($taller->getNombreTaller()) ?>
                                       </option>
                                    <?php endforeach; ?>
                                 </select>
                              </div>
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="area">Área:</label>
                                 <select class="form-select" id="area" name="area" required>
                                    <option value="">Seleccionar Área (opcional)</option>
                                    <?php foreach ($data['areas'] as $area) : ?>
                                       <option value="<?= htmlspecialchars($area->getId()) ?>" <?= (htmlspecialchars($area->getId()) == htmlspecialchars($obrero->getArea())) ? 'selected' : '' ?>>
                                          <?= htmlspecialchars($area->getNombre()) ?>
                                       </option>
                                    <?php endforeach; ?>
                                 </select>
                              </div>
                              
                           <button type="submit" class="btn btn-primary">Actualizar obrero</button>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <script type="text/javascript">
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

      </script>

<?php footerAdmin($data); ?>
