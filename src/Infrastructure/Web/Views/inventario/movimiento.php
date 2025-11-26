<?php headerAdmin($data); ?>
<div class="container-fluid content-inner mt-n5 py-0 pt-5">
    <div class="row mt-4">
        <div class="col-md-12">
            <?php if (!empty($_SESSION['success'])) { echo '<div class="alert alert-success" role="alert">' . $_SESSION['success'] . '</div>'; unset($_SESSION['success']); } ?>
            <?php if (!empty($_SESSION['error'])) { echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error'] . '</div>'; unset($_SESSION['error']); } ?>
        </div>

        <!-- Columna de Información del Artículo y Formulario -->
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?= $data['title'] ?></h4>
                </div>
                <div class="card-body">
                    <h5>Artículo: <?= htmlspecialchars($data['articulo']->getNombre()) ?></h5>
                    <p><strong>Código:</strong> <?= htmlspecialchars($data['articulo']->getCodigo()) ?></p>
                    <p><strong>Stock Actual:</strong> <span class="badge bg-primary fs-5"><?= $data['articulo']->getCantidad() ?></span></p>
                    <hr>
                    
                    <h5 class="mt-4">Registrar Movimiento</h5>
                    <form action="<?= base_url() ?>/inventario/registrarMovimiento" method="post">
                        <input type="hidden" name="id_inventario" value="<?= $data['articulo']->getId() ?>">
                        
                        <div class="mb-3">
                            <label for="tipo_movimiento" class="form-label">Tipo de Movimiento</label>
                            <select class="form-select" id="tipo_movimiento" name="tipo_movimiento" required>
                                <option value="entrada">Entrada (Añadir stock)</option>
                                <option value="salida">Salida (Restar stock)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required>
                        </div>

                        <div class="mb-3">
                            <label for="motivo" class="form-label">Motivo</label>
                            <input type="text" class="form-control" id="motivo" name="motivo" placeholder="Razón del movimiento">
                        </div>

                        <button type="submit" class="btn btn-primary">Registrar Movimiento</button>
                        <a href="<?= base_url() ?>/inventario/listar" class="btn btn-secondary">Volver al Listado</a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Columna del Historial de Movimientos -->
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Historial de Movimientos</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table-movimientos" class="table table-striped" data-toggle="data-table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Cantidad</th>
                                    <th>Motivo</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php foreach ($data['movimientos'] as $movimiento): ?>
                                        <tr>
                                            <td><?= htmlspecialchars(date('d/m/Y H:i:s', strtotime($movimiento['fecha_movimiento']))) ?></td>
                                            <td>
                                                <?php if ($movimiento['tipo_movimiento'] == 'entrada'): ?>
                                                    <span class="badge bg-success">Entrada</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Salida</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($movimiento['cantidad']) ?></td>
                                            <td><?= htmlspecialchars($movimiento['motivo'] ?? '-') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php footerAdmin($data); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stockActual = <?= $data['articulo']->getCantidad() ?>;
        const tipoMovimientoSelect = document.getElementById('tipo_movimiento');
        const cantidadInput = document.getElementById('cantidad');

        function validarStock() {
            const tipo = tipoMovimientoSelect.value;
            const cantidad = parseInt(cantidadInput.value) || 0;

            if (tipo === 'salida' && cantidad > stockActual) {
                cantidadInput.setCustomValidity('La cantidad a retirar no puede ser mayor al stock actual (' + stockActual + ').');
                // Forzar que se muestre el mensaje de error inmediatamente
                cantidadInput.reportValidity();
            } else {
                cantidadInput.setCustomValidity('');
            }
        }

        tipoMovimientoSelect.addEventListener('change', validarStock);
        cantidadInput.addEventListener('input', validarStock);
    });
</script>