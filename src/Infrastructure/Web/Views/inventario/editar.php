<?php headerAdmin($data); ?>
<div class="container-fluid content-inner mt-n5 py-0 pt-5">
  <div class="row mt-4">
    <div class="col-md-8 mx-auto">
      <?php if ($data['mensaje'] != '') echo $data['mensaje']; ?>
      <div class="card">
          <div class="card-header d-flex justify-content-between">
              <div class="header-title">
                <h4 class="card-title"><?= $data['title'] ?></h4>
              </div>
          </div> 
          <div class="card-body">
              <div class="new-user-info">
              <form action="<?= base_url() ?>/inventario/actualizar" method="post">
                <input type="hidden" name="id" value="<?= $data['articulo']->getId() ?>">
                <div class="row">
                    <div class="form-group col-md-6">
                      <label class="form-label" for="codigo">Código:</label>
                      <input id="codigo" type="text" name="codigo" class="form-control" value="<?= htmlspecialchars($data['articulo']->getCodigo()) ?>" required autofocus />
                    </div>
                    <div class="form-group col-md-6">
                      <label class="form-label" for="nombre">Nombre del Artículo:</label>
                      <input id="nombre" type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($data['articulo']->getNombre()) ?>" required />
                    </div>
                    <div class="form-group col-md-6">
                      <label class="form-label" for="marca">Marca:</label>
                      <input id="marca" type="text" name="marca" class="form-control" value="<?= htmlspecialchars($data['articulo']->getMarca()) ?>" />
                    </div>
                    <div class="form-group col-md-6">
                        <label for="tipo" class="form-label">Tipo:</label>
                        <select class="form-select" id="tipo" name="tipo" required>
                            <option value="Materia prima" <?= $data['articulo']->getTipo() == 'Materia prima' ? 'selected' : '' ?>>Materia prima</option>
                            <option value="Herramienta" <?= $data['articulo']->getTipo() == 'Herramienta' ? 'selected' : '' ?>>Herramienta</option>
                            <option value="Consumible" <?= $data['articulo']->getTipo() == 'Consumible' ? 'selected' : '' ?>>Consumible</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?= htmlspecialchars($data['articulo']->getDescripcion()) ?></textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="id_taller" class="form-label">Taller Asignado:</label>
                        <select class="form-select" id="id_taller" name="id_taller" required>
                            <?php foreach ($data['talleres'] as $taller) : ?>
                                <option value="<?= htmlspecialchars($taller->getId()) ?>"
                                    <?= ($taller->getId() == $data['articulo']->getIdTaller()) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($taller->getNombreTaller()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary">Actualizar Artículo</button>
                <a href="<?= base_url() ?>/inventario/listar" class="btn btn-secondary">Cancelar</a>
              </form>
              </div>
          </div>
        </div>
    </div>
  </div>
</div>
<?php footerAdmin($data); ?>