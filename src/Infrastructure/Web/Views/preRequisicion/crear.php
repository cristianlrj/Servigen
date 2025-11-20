<?php headerAdmin($data); ?>

<div class="container-fluid content-inner mt-n5 py-0 pt-5">
 <div class="row mt-4">
 <div class="col-sm-12">
 <div class="card">
 <div class="card-header d-flex justify-content-between">
 <div class="header-title">
 <h4 class="card-title"><?= $data['title'] ?></h4>
 </div>
 </div>
 <div class="card-body">
 <form action="<?= base_url() ?>/preRequisicion/guardar" method="POST">
 <input type="hidden" name="umypf_n" value="">

 <h5 class="mb-3">Reporte de Falla #<input type="text" class="form-control d-inline-block w-auto"  ></h5>
 <p><strong>Unidad Solicitante:</strong> <input type="text" class="form-control d-inline-block w-auto"  ></p>
 <p><strong>Descripción de la Falla:</strong> <textarea class="form-control" rows="3" ></textarea></p>

 <hr>

 <h3>Materiales Requeridos</h3>
 <p>Seleccione los materiales y las cantidades necesarias del inventario del taller.</p>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Artículo</th>
                            <th>Descripción</th>
                            <th>Stock Actual</th>
                            <th>Cantidad a Solicitar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['materiales'])): ?>
                            <tr>
                                <td colspan="4" class="text-center">No hay materiales (Materia prima o Consumibles) disponibles en el taller asociado a esta falla.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['materiales'] as $material): ?>
                                <tr>
                                    <td><?= htmlspecialchars($material->getNombre()) ?></td>
                                    <td><?= htmlspecialchars($material->getDescripcion()) ?></td>
                                    <td><?= htmlspecialchars($material->getCantidad()) ?></td>
                                    <td>
                                        <input type="number" name="materiales[<?= $material->getId() ?>]" class="form-control" min="0" max="<?= htmlspecialchars($material->getCantidad()) ?>">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Guardar Pre-Requisición</button>
                    <a href="<?= base_url() ?>/PreRequisicion/listar/" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
 </div>
 </div>
</div>

<?php footerAdmin($data); ?>