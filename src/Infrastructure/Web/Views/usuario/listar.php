<?php
headerAdmin($data);
?>

<div class="container-fluid content-inner mt-n5 py-0 pt-5">
   <div class="row mt-4">
      <div class="col-sm-12">
            <?php if ($data['mensaje'] != '') echo $data['mensaje']; ?>
         <div class="card">
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title">Usuarios</h4>
               </div>
               <a href="<?= base_url() ?>/usuario/crear" class="btn btn-icon btn-primary fw-bold">Agregar usuario <i class="fa-solid fa-plus"></i></a>
            </div>
            <div class="card-body">
               <div class="table-responsive">
                  <table id="datatable" class="table table-striped" data-toggle="data-table">
                     <thead>
                        <tr>
                           <th>Nombre</th>
                           <th>Apellido</th>
                           <th>Usuario</th>
                           <th>Correo</th>
                           <th>Rol</th>
                           <th>Opciones</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach ($data['usuarios'] as $usuario) { ?>
                        <tr>
                           <td><?= $usuario->getNombre() ?></td>
                           <td><?= $usuario->getApellido() ?></td>
                           <td><?= $usuario->getNombreUsuario() ?></td>
                           <td><?= $usuario->getEmail() ?></td>
                        <!--Colocar el nombre del rol-->
                          <?php foreach ($data['roles'] as $rol) { ?>
                            <?php if ($rol->getId() == $usuario->getRolId()) { ?>
                           <td><?= $rol->getNombreRol() ?></td>
                          <?php } ?>
                          <?php } ?>
                          <td>
                              <?php if ($usuario->getId() != $_SESSION['usuario_id']) { ?>
                           <a href="<?= base_url() ?>/usuario/editar/<?= $usuario->getId() ?>" class="btn btn-icon btn-primary fs-5" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar Usuario"><i class="fa-solid fa-pencil"></i></a>
                           <a href="<?= base_url() ?>/usuario/eliminar/<?= $usuario->getId() ?>" class="btn btn-icon btn-danger fs-5" data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar Usuario"><i class="fa-solid fa-trash"></i></a>
                           <?php } ?>
                        </td>
                        </tr>
                        <?php } ?>
                        
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php
footerAdmin($data);
?>
<script>
    // Inicializar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
