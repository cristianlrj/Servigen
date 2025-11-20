<?php
headerAdmin($data);
?>

    <div class="container-fluid content-inner mt-n5 py-0 pt-5">
    <div class="row mt-4">
            <div class="col-md-12">
                <?php if ($data['mensaje'] != '') echo $data['mensaje']; ?>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Crear Articulo</h4>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url() ?>/inventario/registrar" method="POST">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre del Artículo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="codigo" class="form-label">Código</label>
                                <input type="text" class="form-control" id="codigo" name="codigo" required>
                            </div>
                            <div class="mb-3">
                                <label for="marca" class="form-label">Marca</label>
                                <input type="text" class="form-control" id="marca" name="marca" required>
                            </div>
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="">Seleccione un tipo</option>
                                    <option value="Materia prima">Materia prima</option>
                                    <option value="Herramienta">Herramienta</option>
                                    <option value="Consumible">Consumible</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="cantidad" class="form-label">Cantidad</label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required>
                            </div>
                            <div class="mb-3">
                                <label for="id_taller" class="form-label">Taller</label>
                                <select class="form-select" id="id_taller" name="id_taller" required>
                                    <option value="">Seleccione un taller</option>
                                    <?php foreach ($talleres as $taller) : ?>
                                        <option value="<?= htmlspecialchars($taller->getId()) ?>">
                                            <?= htmlspecialchars($taller->getNombreTaller()) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Registrar Artículo</button>
                            <a href="<?= base_url() ?>/inventario/listar" class="btn btn-secondary"><i class="fa-solid fa-xmark"></i> Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php footerAdmin($data); ?>