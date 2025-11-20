<?php headerAdmin($data); ?>
<div class="conatiner-fluid content-inner mt-n5 py-0 pt-5">
         <div class="row mt-4">
            <div class="col">
               <div class="card">
                  <div class="card-header d-flex justify-content-between">
                     <div class="header-title">
                        <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                           <?php echo $_SESSION['error']; ?>
                        </div>
                        <?php endif; ?>
                        <h4 class="card-title">Editar usuario</h4>
                     </div>
                  </div> 
                  <div class="card-body">
                     <div class="new-user-info">
                        <form action="<?= base_url() ?>/usuario/registrar" method="post">
                           <input type="hidden" name="id" value="<?= $data['usuario']->getId() ?>">
                           <div class="row">
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="nombre">Nombre:</label>
                                 <input type="text" name="nombre" maxlength="25" required class="form-control" id="nombre" value="<?= $data['usuario']->getNombre() ?>" placeholder="Nombre">
                              </div>
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="apellido">Apellido:</label>
                                 <input type="text" name="apellido" maxlength="25" required class="form-control" id="apellido" value="<?= $data['usuario']->getApellido() ?>" placeholder="Apellido">
                              </div>
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="usuario">Usuario:</label>
                                 <input type="text" name="usuario" maxlength="25" required class="form-control" id="usuario" value="<?= $data['usuario']->getNombreUsuario() ?>" placeholder="Usuario">
                              </div>
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="email">Email:</label>
                                 <input type="email" name="email" maxlength="50" required class="form-control" id="email" value="<?= $data['usuario']->getEmail() ?>" placeholder="Email">
                              </div>
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="password">Contraseña:</label>
                                 <input type="password" name="password" class="form-control" id="password" placeholder="Contraseña">
                              </div>
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="confirm-password">Confirmar Contraseña:</label>
                                 <input type="password" name="confirm-password" class="form-control" id="confirm-password" placeholder="Confirmar Contraseña">
                              </div>
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="rol">Rol:</label>
                                 <select name="rol" id="rol" required class="selectpicker form-control" data-style="py-0">
                                    <option value="">--Seleccione--</option>
                                    <?php foreach ($roles as $rol): ?>
                                        <option value="<?= $rol->getId() ?>" <?= $rol->getId() == $data['usuario']->getRolId() ? 'selected' : '' ?>><?= $rol->getNombreRol() ?></option>
                                    <?php endforeach; ?>
                                 </select>
                              </div>
                              <div class="form-group col-md-6">
                                 <!-- el taller es requerido si el rol es jefe de taller -->
                                 <label class="form-label" for="taller">Taller:</label>
                                 <select name="taller" id="taller" disabled class="selectpicker form-control" data-style="py-0">
                                    <option value="">--Seleccione--</option>
                                    <?php foreach ($talleres as $taller): ?>
                                        <option value="<?= $taller->getId() ?>" <?= $taller->getId() == $data['usuario']->getTallerId() ? 'selected' : '' ?>><?= $taller->getNombreTaller() ?></option>
                                    <?php endforeach; ?>
                                 </select>
                              </div>
                              
                           <button type="submit" class="btn btn-primary">Actualizar usuario</button>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <script type="text/javascript">
         // si el rol es jefe de taller, se habilita el campo de taller
         <?php if ($data['usuario']->getRolId() == 3) { ?>
            document.getElementById('taller').disabled = false;
            document.getElementById('taller').required = true;
         <?php } ?>
         document.getElementById('rol').addEventListener('change', function() {
            if (this.value == 3) {
               document.getElementById('taller').disabled = false;
               document.getElementById('taller').required = true;
            } else {
               document.getElementById('taller').disabled = true;
               document.getElementById('taller').required = false;
            }
         });

         // si la contraseña no coincide, se muestra un mensaje de error
         document.getElementById('confirm-password').addEventListener('change', function() {
            if (this.value != document.getElementById('password').value) {
              document.getElementById('confirm-password').setCustomValidity('Las contraseñas no coinciden');
            } else {
              document.getElementById('confirm-password').setCustomValidity('');
            }
         });

         //los campos tipo texto no deben admitir numeros
         document.getElementById('nombre').addEventListener('change', function() {
            if (this.value.match(/[^a-zA-Z\s]/)) {
              document.getElementById('nombre').setCustomValidity('El nombre solo debe contener letras');
            } else {
              document.getElementById('nombre').setCustomValidity('');
            }
         });

         document.getElementById('apellido').addEventListener('change', function() {
            if (this.value.match(/[^a-zA-Z\s]/)) {
              document.getElementById('apellido').setCustomValidity('El apellido solo debe contener letras');
            } else {
              document.getElementById('apellido').setCustomValidity('');
            }
         });

      </script>

<?php footerAdmin($data); ?>