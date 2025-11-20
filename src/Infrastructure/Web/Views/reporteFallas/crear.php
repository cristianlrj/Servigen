<?php headerAdmin($data); ?>
<div class="container-fluid content-inner mt-n5 py-0 pt-5">
  <div class="row mt-4">
    <div class="col">
      <?php if ($data['mensaje'] != '') echo $data['mensaje']; ?>
      <div class="card">
                  <div class="card-header d-flex justify-content-between">
                     <div class="header-title">
                        <h4 class="card-title">Crear nuevo reporte de fallas</h4>
                     </div>
                  </div> 
                  <div class="card-body">
                     <div class="new-user-info">
                     <form action="<?= base_url() ?>/reporteFallas/guardar" method="post">
 <div class="row">
 <div class="form-group col-md-6">
 <label class="form-label" for="umypf_n">UMYPF-N°:</label>
 <input id="umypf_n" type="text" name="umypf_n" value="00000" disabled class="form-control" />
 </div>

 <div class="form-group col-md-6">
 <label class="form-label" for="unidad_solicitante">Unidad Solicitante:</label>
 <select id="unidad_solicitante" name="unidad_solicitante" required class="form-select">
 <option value="" disabled selected>Seleccione una unidad solicitante</option>
 <?php foreach ($data['departamentos'] as $departamento): ?>
 <option value="<?= htmlspecialchars($departamento->getId()) ?>"><?= htmlspecialchars($departamento->getNombre()) ?></option>
 <?php endforeach; ?>
 </select>
 </div>

 <div class="form-group col-md-6">
 <label class="form-label" for="persona_contacto">Persona de Contacto:</label>
 <input id="persona_contacto" type="text" name="persona_contacto" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" class="form-control" />
 </div>

 <div class="form-group col-md-6">
 <label class="form-label" for="email_contacto">Correo de Contacto (para encuesta):</label>
 <input id="email_contacto" type="email" name="email_contacto" class="form-control" />
 </div>

 <div class="form-group col-md-6">
 <label class="form-label" for="id_taller">Taller:</label>
 <select id="id_taller" name="id_taller" required class="form-select">
 <option value="" disabled selected>Seleccione un taller</option>
 <?php foreach ($data['talleres'] as $taller): ?>
 <option value="<?= $taller->getId() ?>"><?= htmlspecialchars($taller->getNombreTaller()) ?></option>
 <?php endforeach; ?>
 </select>
 </div>

 <div class="form-group col-md-6">
 <label class="form-label" for="usuario">Usuario:</label>
 <input id="usuario" type="text" value="<?= $data['username'] ?>" disabled class="form-control" />
 <input type="hidden" name="id_usuario" value="<?= $_SESSION['usuario_id'] ?>" />
 </div>

 <div class="form-group col-md-12">
 <label class="form-label" for="descripcion_falla">Descripción de la Falla:</label>
 <textarea id="descripcion_falla" name="descripcion" placeholder="Ingrese la descripción" required class="form-control" rows="3"></textarea>
 </div>

 <div class="col-md-12 mt-3">
 <button type="submit" class="btn btn-primary">Guardar Reporte</button>
 </div>
 </div>
 </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <script type="text/javascript">
         // Validación para el título
         document.getElementById('titulo').addEventListener('change', function() {
            if (this.value.length < 5) {
              document.getElementById('titulo').setCustomValidity('El título debe tener al menos 5 caracteres');
            } else {
              document.getElementById('titulo').setCustomValidity('');
            }
         });

         // Validación para la descripción
         document.getElementById('descripcion').addEventListener('change', function() {
            if (this.value.length < 10) {
              document.getElementById('descripcion').setCustomValidity('La descripción debe tener al menos 10 caracteres');
            } else {
              document.getElementById('descripcion').setCustomValidity('');
            }
         });

      </script>

<?php footerAdmin($data); ?>
