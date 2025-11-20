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
               <form action="<?= base_url() ?>/satisfaccion/guardarSatisfaccion" method="POST">
                  <input type="hidden" name="umypf_n" value="<?= $data['falla']->getId() ?>">
                  <div class="form-group mb-3">
                     <label for="satisfaccion" class="form-label">Nivel de Satisfacción:</label>
                     <div class="d-flex flex-column">
                        <div class="form-check">
                        <input class="form-check-input" type="radio" name="satisfaccion" id="satisfaccion5" value="5" required>
                        <label class="form-check-label" for="satisfaccion5">
                        5 - Excelente
                        </label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" type="radio" name="satisfaccion" id="satisfaccion4" value="4">
                        <label class="form-check-label" for="satisfaccion4">
                        4 - Muy Bueno
                        </label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" type="radio" name="satisfaccion" id="satisfaccion3" value="3">
                        <label class="form-check-label" for="satisfaccion3">
                        3 - Bueno
                        </label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" type="radio" name="satisfaccion" id="satisfaccion2" value="2">
                        <label class="form-check-label" for="satisfaccion2">
                        2 - Regular
                        </label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" type="radio" name="satisfaccion" id="satisfaccion1" value="1">
                        <label class="form-check-label" for="satisfaccion1">
                        1 - Malo
                        </label>
                        </div>
                        </div>
                  </div>
                  <div class="form-group mb-3">
                     <label for="comentarios" class="form-label">Comentarios Adicionales:</label>
                     <textarea class="form-control" id="comentarios" name="comentarios" rows="3"></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary">Guardar Satisfacción</button>
                  <a href="<?= base_url() ?>/reporteFallas/listar" class="btn btn-secondary">Volver</a>
                </form>
            </div>
         </div>
      </div>
   </div>
</div>

<?php
footerAdmin($data);
?>