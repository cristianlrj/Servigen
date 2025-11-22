<?php headerAdmin($data); ?>

<div class="container-fluid content-inner pt-5">

    <div class="row mb-2 mt-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Dashboard</h2>
            
            <!-- Filter Form -->
            <form method="GET" action="" class="d-flex align-items-center">
                <?php
                    $meses = ["", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
                    $selectedMonth = $data['selected_month'] ?? date('n');
                    $selectedYear = $data['selected_year'] ?? date('Y');
                ?>
                <select name="month" class="form-select me-2" onchange="this.form.submit()">
                    <option value="all" <?= $selectedMonth === 'all' ? 'selected' : '' ?>>Todos los tiempos</option>
                    <?php for($i=1; $i<=12; $i++): ?>
                        <option value="<?= $i ?>" <?= ($selectedMonth != 'all' && (int)$selectedMonth == $i) ? 'selected' : '' ?>>
                            <?= $meses[$i] ?>
                        </option>
                    <?php endfor; ?>
                </select>
                
                <select name="year" class="form-select" onchange="this.form.submit()" <?= $selectedMonth === 'all' ? 'disabled' : '' ?>>
                    <?php 
                        $currentYear = date('Y');
                        for($y = $currentYear; $y >= $currentYear - 5; $y--): 
                    ?>
                        <option value="<?= $y ?>" <?= ($selectedYear == $y) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </form>
        </div>
    </div>
    
    <div class="row">

        <!-- Charts & KPIs -->
        <div class="col-md-12 col-lg-6">
            <!-- Chart JS Section -->
            <div class="card" data-aos="fade-up" data-aos-delay="500">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Estado de Fallas</h4>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="fallasChart" style="max-height: 250px;"></canvas>
                </div>
            </div>

            <!-- Tiempos Promedio -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card" data-aos="fade-up" data-aos-delay="600">
                        <div class="card-body">
                             <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-2 card-title">Tiempo Aceptación</h6>
                                </div>
                                <i class="fa-solid fa-hourglass-start fa-2x text-info"></i>
                             </div>
                             <div class="mt-3">
                                <h4 class="counter text-info"><?= $data['tiempo_promedio_aceptacion'] ?? 'N/A' ?></h4>
                                <small class="text-muted">Meta: 2 Horas</small>
                             </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card" data-aos="fade-up" data-aos-delay="600">
                        <div class="card-body">
                             <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-2 card-title">Tiempo Solución</h6>
                                </div>
                                <i class="fa-solid fa-stopwatch fa-2x text-primary"></i>
                             </div>
                             <div class="mt-3">
                                <h4 class="counter text-primary"><?= $data['tiempo_promedio_solucion'] ?? 'N/A' ?></h4>
                                <small class="text-muted">Meta: 2 dia</small>
                             </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Satisfaccion -->
            <div class="card" data-aos="fade-up" data-aos-delay="600">
                <div class="card-body">
                     <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-2 card-title">Satisfacción Promedio</h5>
                        </div>
                        <i class="fa-solid fa-ranking-star fa-3x text-warning"></i>
                     </div>
                     <div class="mt-3">
                        <h2 class="counter text-warning"><?= $data['promedio_satisfaccion'] ?? '0' ?>%</h2>
                        <div class="progress mt-2" style="height: 10px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $data['promedio_satisfaccion'] ?? '0' ?>%" aria-valuenow="<?= $data['promedio_satisfaccion'] ?? '0' ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="text-muted mt-1 d-block">(Meta: 90%)</span>
                     </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Total Mantenimientos y Tareas Recientes -->
        <div class="col-md-12 col-lg-6">
            
            <!-- Total Mantenimientos -->
            <div class="card card-slide" data-aos="fade-up" data-aos-delay="700">
                <div class="card-body">
                    <div class="progress-widget">
                        <div class="text-center circle-progress-01 circle-progress circle-progress-primary">
                            <i class="fa-solid fa-screwdriver-wrench fa-2x"></i>
                        </div>
                        <div class="progress-detail">
                            <p class="mb-2">Total Mantenimientos</p>
                            <h4 class="counter"><?= $data['total_mantenimientos'] ?? '0' ?></h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tareas Recientes -->
            <div class="card" data-aos="fade-up" data-aos-delay="700">
                <div class="flex-wrap card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="mb-2 card-title">Tareas Recientes</h4>
                        <p class="mb-0">Solucionadas esta semana</p>
                    </div>
                </div>
                <div class="card-body">
                    <?php $tareas = $data['tareas_recientes'] ?? []; ?>
                    <?php if (empty($tareas)): ?>
                        <p class="text-center text-muted">No hay tareas recientes.</p>
                    <?php else: ?>
                        <?php foreach ($tareas as $tarea): ?>
                            <div class="mb-3 d-flex profile-media align-items-top">
                                <div class="mt-1 me-3">
                                    <i class="fa-solid fa-check text-success fa-lg"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1"><?= htmlspecialchars($tarea['titulo'] ?? 'Tarea sin título') ?></h6>
                                    <span class="mb-0 text-muted"><?= htmlspecialchars($tarea['fecha'] ?? 'Sin fecha') ?></span>
                                </div>
                                <div class="ms-auto">
                                    <?php
                                        $satisfaccion = $tarea['satisfaccion'] ?? null;
                                        $colorClass = 'text-muted';
                                        if ($satisfaccion !== null) {
                                            $val = (int)$satisfaccion;
                                            if ($val == 5) $colorClass = 'text-success';
                                            elseif ($val == 4) $colorClass = 'text-info';
                                            elseif ($val == 3) $colorClass = 'text-warning';
                                            elseif ($val <= 2) $colorClass = 'text-danger';
                                        }                                        
                                    ?>
                                    <span class="mb-0 badge rounded-circle border border-2 p-2 <?= $colorClass ?> border-<?= str_replace('text-', '', $colorClass) ?>" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <?= htmlspecialchars($satisfaccion ?? 'N/A') ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div> 
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('fallasChart').getContext('2d');
    const fallasChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Pendientes', 'En Proceso', 'Solucionadas'],
            datasets: [{
                label: 'Fallas',
                data: [
                    <?= $data['fallas_pendientes'] ?? 0 ?>, 
                    <?= $data['fallas_en_proceso'] ?? 0 ?>, 
                    <?= $data['fallas_solucionadas'] ?? 0 ?>
                ],
                backgroundColor: [
                    '#dc3545', // Danger (Pendientes)
                    '#0dcaf0', // Info (En Proceso)
                    '#198754'  // Success (Solucionadas)
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});
</script>

<?php footerAdmin($data); ?>