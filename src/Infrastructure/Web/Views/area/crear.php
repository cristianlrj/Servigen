<?php headerAdmin($data); ?>
<div class="container-fluid content-inner mt-n5 py-0 pt-5">
  <div class="row mt-4">
    <div class="col-md-8 mx-auto">
      <?php if (!empty($_SESSION['error'])) { echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error'] . '</div>'; unset($_SESSION['error']); } ?>
      <div class="card">
          <div class="card-header d-flex justify-content-between">
              <div class="header-title">
                <h4 class="card-title"><?= $data['title'] ?></h4>
              </div>
          </div> 
          <div class="card-body">
              <div class="new-user-info">
              <form action="<?= base_url() ?>/area/registrar" method="post">
                <div class="row">
                    <div class="form-group col-md-12">
                      <label class="form-label" for="nombre">Nombre del Área:</label>
                      <input id="nombre" type="text" name="nombre" class="form-control" required autofocus />
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?= base_url() ?>/area/listar" class="btn btn-secondary">Cancelar</a>
              </form>
              </div>
          </div>
        </div>
    </div>
  </div>
</div>
<?php footerAdmin($data); ?>