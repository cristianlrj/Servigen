<?php headerAdmin($data); ?>
<div class="container-fluid content-inner mt-n5 py-0 pt-5">
  <div class="row mt-4">
    <div class="col">
      <?php if (!empty($data['mensaje'])) echo $data['mensaje']; ?>
      <div class="card">
          <div class="card-header d-flex justify-content-between">
             <div class="header-title">
                <h4 class="card-title">Programar Nuevo Mantenimiento Preventivo</h4>
             </div>
          </div> 
          <div class="card-body">
             <div class="new-user-info">
             <form action="<?= base_url() ?>/mantenimientoPreventivo/registrar" method="post">
                 <div class="row">
                     <div class="form-group col-md-6">
                         <label class="form-label" for="nombre_equipo">Nombre del Equipo/Área:</label>
                         <input id="nombre_equipo" type="text" name="nombre_equipo" required class="form-control" placeholder="Ej: Aire Acondicionado Sala de Servidores" />
                     </div>

                     <div class="form-group col-md-6">
                         <label class="form-label" for="id_taller">Taller a Asignar:</label>
                         <select id="id_taller" name="id_taller" required class="form-select">
                             <option value="" disabled selected>Seleccione un taller</option>
                             <?php foreach ($data['talleres'] as $taller): ?>
                                 <option value="<?= $taller->getId() ?>"><?= htmlspecialchars($taller->getNombreTaller()) ?></option>
                             <?php endforeach; ?>
                         </select>
                     </div>

                     <div class="form-group col-md-6">
                         <label class="form-label" for="tipo_mantenimiento">Tipo de Mantenimiento:</label>
                         <input id="tipo_mantenimiento" type="text" name="tipo_mantenimiento" required class="form-control" placeholder="Ej: Limpieza de Filtros" />
                     </div>

                     <div class="form-group col-md-6">
                         <label class="form-label" for="fecha_programada">Fecha Programada:</label>
                         <input id="fecha_programada" type="date" name="fecha_programada" required class="form-control" />
                     </div>

                     <div class="form-group col-md-12">
                         <label class="form-label" for="descripcion_tarea">Descripción de la Tarea:</label>
                         <textarea id="descripcion_tarea" name="descripcion_tarea" placeholder="Ingrese los detalles de la tarea a realizar" required class="form-control" rows="3"></textarea>
                     </div>

                     <div class="form-group col-md-6">
                         <label class="form-label" for="usuario">Usuario que registra:</label>
                         <input id="usuario" type="text" value="<?= $data['username'] ?>" disabled class="form-control" />
                         <input type="hidden" name="id_usuario" value="<?= $_SESSION['usuario_id'] ?>" />
                     </div>

                     <div class="col-md-12 mt-3">
                         <button type="submit" class="btn btn-primary">Programar Mantenimiento</button>
                         <a href="<?= base_url() ?>/mantenimientoPreventivo/listar" class="btn btn-secondary">Cancelar</a>
                     </div>
                 </div>
             </form>
             </div>
          </div>
       </div>
    </div>
 </div>
</div>

<?php footerAdmin($data); ?>