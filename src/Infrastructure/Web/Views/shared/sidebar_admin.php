 <!-- Sidebar Menu Start -->
 <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
     <li class="nav-item static-item">
         <a class="nav-link static-item disabled" href="<?= base_url() ?>/dashboard/index" tabindex="-1">
             <span class="default-icon">Inicio</span>
             <span class="mini-icon">-</span>
         </a>
     </li>
     <li class="nav-item">
         <a class="nav-link" aria-current="page" href="<?= base_url() ?>/dashboard/index">
             <i class="fa-solid fa-chart-line"></i>
             <span class="item-name">Dashboard</span>
         </a>
     </li>

     <li>
         <hr class="hr-horizontal">
     </li>
     <li class="nav-item static-item">
         <a class="nav-link static-item disabled" href="#" tabindex="-1">
             <span class="default-icon">Opciones</span>
             <span class="mini-icon">-</span>
         </a>
     </li>
     <li class="nav-item">
         <a class="nav-link" href="<?= base_url() ?>/usuario/listar">
             <i class="fa-solid fa-users"></i>
             <span class="item-name">Usuarios</span>
         </a>
     </li>
     <li class="nav-item">
         <a class="nav-link" href="<?= base_url() ?>/obrero/listar">
             <i class="fa-solid fa-briefcase"></i>
             <span class="item-name">Obreros</span>
         </a>
         <ul class="sub-nav collapse" id="obreros" data-bs-parent="#sidebar-menu">            
             <li class="nav-item">
             </li>
         </ul>
     </li>
     <li class="nav-item">
         <a class="nav-link" href="<?= base_url() ?>/reporteFallas/listar">
             <i class="fa-solid fa-bullhorn"></i>
             <span class="item-name">Reporte de fallas</span>
         </a>
         <ul class="sub-nav collapse" id="d-menu" data-bs-parent="#sidebar-menu">            
         </ul>
     </li>
     <li class="nav-item">
         <a class="nav-link" href="<?= base_url() ?>/PreRequisicion/listar">
             <i class="fa-solid fa-file-lines"></i>
             <span class="item-name">Pre requisiciones</span>
         </a>
         <ul class="sub-nav collapse" id="horizontal-menu" data-bs-parent="#sidebar-menu">            
         </ul>
     </li>
     <li class="nav-item">
         <a class="nav-link" href="<?= base_url() ?>/inventario/listar">
             <i class="fa-solid fa-warehouse"></i>
             <span class="item-name">Almacén</span>
         </a>
         <ul class="sub-nav collapse" id="inventario" data-bs-parent="#inventario">            
         </ul>
     </li>
     <li class="nav-item">
         <a class="nav-link" href="<?= base_url() ?>/taller/listar">
             <i class="fa-solid fa-screwdriver-wrench"></i>
             <span class="item-name">Talleres</span>
         </a>
         <ul class="sub-nav collapse" id="horizontal-menu" data-bs-parent="#sidebar-menu">            
         </ul>
     </li>
     <li class="nav-item">
         <a class="nav-link" href="<?= base_url() ?>/departamento/listar">
             <i class="fa-solid fa-building"></i>
             <span class="item-name">Departamentos</span>
         </a>
         <ul class="sub-nav collapse" id="departamentos" data-bs-parent="#sidebar-menu">            
         </ul>
     </li>
     <li class="nav-item">
         <a class="nav-link" href="<?= base_url() ?>/area/listar">
             <i class="fa-solid fa-map-location-dot"></i>
             <span class="item-name">Áreas</span>
         </a>
         <ul class="sub-nav collapse" id="areas" data-bs-parent="#sidebar-menu">            
         </ul>
     </li>
     <li class="nav-item">
         <a class="nav-link" href="<?= base_url() ?>/mantenimientoPreventivo/listar">
             <i class="fa-solid fa-screwdriver-wrench"></i>
             <span class="item-name">Mantenimientos</span>
         </a>
         <ul class="sub-nav collapse" id="mantenimientoPreventivo" data-bs-parent="#sidebar-menu">
         </ul>
     </li>
 </ul>
 <!-- Sidebar Menu End -->