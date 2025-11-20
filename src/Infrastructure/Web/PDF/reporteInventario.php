<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?></title>
    <link rel="stylesheet" href="pdf.css">
</head>
<body>
    <div class="header">
        <img src="<?= base_url() ?>/public/assets/images/cintillo.jpeg" class="cintillo">
        <h1><?= $titulo ?></h1>
        <p>Fecha de generación: <?= date('d/m/Y H:i:s') ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Marca</th>
                <th>Tipo</th>
                <th>Descripción</th>
                <th>Stock</th>
                <th>Taller</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($inventario)): ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No hay artículos en el inventario para mostrar con los filtros seleccionados.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($inventario as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item->getCodigo()) ?></td>
                        <td><?= htmlspecialchars($item->getNombre()) ?></td>
                        <td><?= htmlspecialchars($item->getMarca()) ?></td>
                        <td><?= htmlspecialchars($item->getTipo()) ?></td>
                        <td><?= htmlspecialchars($item->getDescripcion()) ?></td>
                        <td><?= htmlspecialchars($item->getCantidad()) ?></td>
                        <td><?= isset($talleresMap[$item->getIdTaller()]) ? htmlspecialchars($talleresMap[$item->getIdTaller()]) : 'No asignado' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>