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
                <th>Cédula</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Taller</th>
                <th>Ocupación</th>
                <th>Habilidades</th>
                <th>Ubicación Área</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($obreros)): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No hay obreros para mostrar.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($obreros as $obrero): 
                    $area = $getAreaUseCase->ejecutar($obrero->getArea());

                    ?>
                    <tr>
                        <td><?= htmlspecialchars($obrero->getCedula()) ?></td>
                        <td><?= htmlspecialchars($obrero->getNombre()) ?></td>
                        <td><?= htmlspecialchars($obrero->getApellido()) ?></td>
                        <td><?= isset($talleresMap[$obrero->getTaller()]) ? htmlspecialchars($talleresMap[$obrero->getTaller()]) : 'No asignado' ?></td>
                        <td><?= htmlspecialchars($obrero->getCargo()) ?></td>
                        <td><?= htmlspecialchars($obrero->getHabilidades()) ?></td>
                        <td><?= htmlspecialchars($area->getNombre()) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>