<?php headerAdmin($data); ?>

<div class="container-fluid content-inner pt-5">

    <div class="row mb-2 mt-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Dashboard</h2>
            <?php
                // Array de meses en español (índice 1-based para date('n'))
                $meses = ["", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
                $mesNum = (int)date("n"); // Mes actual como número 1-12
                $ano = date("Y"); // Año actual
                $mesActualStr = $meses[$mesNum] . " de " . $ano;
            ?>
            <h5 class="mb-0 text-muted">
                <i class="fa-solid fa-calendar-days me-2"></i>
                <?php echo $mesActualStr; ?>
            </h5>
        </div>
    </div>
    <div class="row mt-2">
    
        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="700">
            <div class="card card-slide">
                <div class="card-body">
                    <div class="progress-widget">
                        <div class="text-center circle-progress-01 circle-progress circle-progress-primary">
                            <i class="fa-solid fa-briefcase fa-2x"></i>
                        </div>
                        <div class="progress-detail">
                            <p class="mb-2">Total Obreros</p>
                            <h4 class="counter"><?= $data['total_obreros'] ?? '0' ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="800">
            <div class="card card-slide">
                <div class="card-body">
                    <div class="progress-widget">
                        <div class="text-center circle-progress-01 circle-progress circle-progress-danger">
                            <i class="fa-solid fa-clock fa-2x"></i>
                        </div>
                        <div class="progress-detail">
                            <p class="mb-2">Fallas Pendientes</p>
                            <h4 class="counter"><?= $data['fallas_pendientes'] ?? '0' ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="900">
            <div class="card card-slide">
                <div class="card-body">
                    <div class="progress-widget">
                        <div class="text-center circle-progress-01 circle-progress circle-progress-info">
                            <i class="fa-solid fa-person-digging fa-2x"></i>
                        </div>
                        <div class="progress-detail">
                            <p class="mb-2">En Proceso</p>
                            <h4 class="counter"><?= $data['fallas_en_proceso'] ?? '0' ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="1000">
            <div class="card card-slide">
                <div class="card-body">
                    <div class="progress-widget">
                        <div class="text-center circle-progress-01 circle-progress circle-progress-success">
                            <i class="fa-solid fa-circle-check fa-2x"></i>
                        </div>
                        <div class="progress-detail">
                            <p class="mb-2">Solucionadas</p>
                            <h4 class="counter"><?= $data['fallas_solucionadas'] ?? '0' ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    
    <div class="row">

        <div class="col-md-12 col-lg-6">
            <div class="card" data-aos="fade-up" data-aos-delay="500">
                <div class="card-body">
                     <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-2 card-title">Tiempo Promedio</h5>
                            <p class="mb-0 text-secondary">de Aceptación de Falla</p>
                        </div>
                        <i class="fa-solid fa-hourglass-start fa-3x text-info"></i>
                     </div>
                     <div class="mt-3">
                        <h2 class="counter text-info"><?= $data['tiempo_promedio_aceptacion'] ?? 'N/A' ?></h2>
                        <span class="text-muted">(Meta: 2 Horas)</span>
                     </div>
                </div>
            </div>
             <div class="card" data-aos="fade-up" data-aos-delay="600">
                <div class="card-body">
                     <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-2 card-title">Tiempo Promedio</h5>
                            <p class="mb-0 text-secondary">de Solución de Fallas</p>
                        </div>
                        <i class="fa-solid fa-stopwatch fa-3x text-primary"></i>
                     </div>
                     <div class="mt-3">
                        <h2 class="counter text-primary"><?= $data['tiempo_promedio_solucion'] ?? 'N/A' ?></h2>
                        <span class="text-muted">(Meta: 1 dia)</span>
                     </div>
                </div>
            </div>
            <div class="card" data-aos="fade-up" data-aos-delay="600">
                <div class="card-body">
                     <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-2 card-title">Calificacion Promedio</h5>
                            <p class="mb-0 text-secondary"></p>
                        </div>
                        <i class="fa-solid fa-ranking-star fa-3x text-secondary"></i>
                     </div>
                     <div class="mt-3">
                        <h2 class="counter text-primary"><?= $data['promedio_satisfaccion'] ?? 'N/A' ?></h2>
                        <span class="text-muted">(Meta: 5 - Excelente)</span>
                     </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-6">
            <div class="card" data-aos="fade-up" data-aos-delay="700">
                <div class="flex-wrap card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="mb-2 card-title">Tareas Recientes</h4>
                        <p class="mb-0">
                            Solucionadas esta semana
                        </p>
                    </div>
                </div>
                <div class="card-body">
                    <?php 
                        $tareas = $data['tareas_recientes'] ?? []; 
                    ?>

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
                                        $colorClass = 'text-muted'; // Default color
                                        if ($satisfaccion !== null) {
                                            switch ((int)$satisfaccion) {
                                                case 5: $colorClass = 'text-success'; break;
                                                case 4: $colorClass = 'text-info'; break;
                                                case 3: $colorClass = 'text-warning'; break;
                                                case 2: $colorClass = 'text-orange'; break; // Assuming 'text-orange' is defined or similar to a reddish-orange
                                                case 1: $colorClass = 'text-danger'; break;
                                                default: $colorClass = 'text-muted'; break;
                                            }
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

<?php footerAdmin($data); ?>