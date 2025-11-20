


<!doctype html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title><?= APP_NAME ?> - Login</title>
      
      <!-- Favicon -->
     <link rel="shortcut icon" href="<?= media() ?>/images/logo_mini.png">
      
      <!-- Library / Plugin Css Build -->
      <link rel="stylesheet" href="<?= media() ?>/css/core/libs.min.css">
      
      
      <!-- Hope Ui Design System Css -->
      <link rel="stylesheet" href="<?= media() ?>/css/hope-ui.min.css?v=4.0.0">
      
      <!-- Custom Css -->
      <link rel="stylesheet" href="<?= media() ?>/css/custom.min.css?v=4.0.0">
      
      <!-- Dark Css -->
      <link rel="stylesheet" href="<?= media() ?>/css/dark.min.css">
      
      <!-- Customizer Css -->
      <link rel="stylesheet" href="<?= media() ?>/css/customizer.min.css">
      
      <!-- RTL Css -->
      <link rel="stylesheet" href="<?= media() ?>/css/rtl.min.css">
      
      
  </head>
  <body class=" " data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">
    <!-- loader Start -->
    <div id="loading">
      <div class="loader simple-loader">
          <div class="loader-body">
          </div>
      </div>    </div>
    <!-- loader END -->
    
    <div class="wrapper">
       <section class="login-content">
          <div class="row m-0 align-items-center bg-white vh-100">            
             <div class="col-md-7">
                  <div class="fixed-top bg-white shadow-sm d-flex justify-content-center align-items-center" style="height: 70px;">

    <img src="<?= media() ?>/images/cintillo.jpeg" class="mh-100 mw-100" alt="cintillo">
</div>
                  <div class="row justify-content-center">
                   <div class="col-md-10">
                     <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
                        <div class="card-body align-items-center">
                              
                              <!--Logo start-->
                              <div class="logo-main text-center">
                                  <div class="logo-normal">
                                  <img src="<?= media() ?>/images/servigen.png"  height="75"  alt="logo">
                                  </div>
                                  <div class="logo-mini">
                                  <img src="<?= media() ?>/images/servigen.png"  height="75"  alt="logo">
                                  </div>
                              </div>
                              <!--logo End-->                              
                              
                              
                           <center><img src="<?= media() ?>/images/uptos.png"  height="85"  alt="logo"></center>
                           <?php if (isset($data['error'])): ?>
                              <div class="alert alert-danger">
                                 <?= $data['error'] ?>
                              </div>
                           <?php endif; ?>
                           <h2 class="mb-2 text-center">Login</h2>
                           <p class="text-center">Ingresa tus credenciales para acceder al sistema.</p>
                           <form action="<?= base_url() ?>/auth/login" method="post">
                              <div class="row">
                                 <div class="col-lg-12">
                                    <div class="form-group">
                                       <label for="nombre_usuario" class="form-label">Usuario</label>
                                       <input type="text" class="form-control" name="nombre_usuario" id="nombre_usuario" aria-describedby="nombre_usuario" placeholder=" ">
                                    </div>
                                 </div>
                                 <div class="col-lg-12">
                                    <div class="form-group">
                                       <label for="password" class="form-label">Contraseña</label>
                                       <input type="password" class="form-control" name="password" id="password" aria-describedby="password" placeholder=" ">
                                    </div>
                                 </div>
                                 <div class="col-lg-12 d-flex justify-content-between">
                                    <!-- <div class="form-check mb-3">
                                       <input type="checkbox" class="form-check-input" id="customCheck1">
                                       <label class="form-check-label" for="customCheck1">Remember Me</label>
                                    </div> -->
                                    <!-- <a href="recoverpw.html">Forgot Password?</a> -->
                                 </div>
                              </div>
                              <div class="d-flex justify-content-center">
                                 <button type="submit" class="btn btn-primary">Ingresar</button>
                              </div>
                              <!-- <p class="text-center my-3">or sign in with other accounts?</p>
                              <div class="d-flex justify-content-center">
                                 <ul class="list-group list-group-horizontal list-group-flush">
                                    <li class="list-group-item border-0 pb-0">
                                       <a href="#"><img src="<?= media() ?>/images/brands/fb.svg" alt="fb"></a>
                                    </li>
                                    <li class="list-group-item border-0 pb-0">
                                       <a href="#"><img src="<?= media() ?>/images/brands/gm.svg" alt="gm"></a>
                                    </li>
                                    <li class="list-group-item border-0 pb-0">
                                       <a href="#"><img src="<?= media() ?>/images/brands/im.svg" alt="im"></a>
                                    </li>
                                    <li class="list-group-item border-0 pb-0">
                                       <a href="#"><img src="<?= media() ?>/images/brands/li.svg" alt="li"></a>
                                    </li>
                                 </ul>
                              </div> -->
                              <!-- <p class="mt-3 text-center">
                                 Don’t have an account? <a href="sign-up.html" class="text-underline">Click here to sign up.</a>
                              </p> -->
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="sign-bg">
                  <svg width="280" height="230" viewBox="0 0 431 398" fill="none" xmlns="http://www.w3.org/2000/svg">
                     <g opacity="0.05">
                     <rect x="-157.085" y="193.773" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 -157.085 193.773)" fill="#5e2129 "/>
                     <rect x="7.46875" y="358.327" width="543" height="77.5714" rx="38.7857" transform="rotate(-45 7.46875 358.327)" fill="#5e2129 "/>
                     <rect x="61.9355" y="138.545" width="310.286" height="77.5714" rx="38.7857" transform="rotate(45 61.9355 138.545)" fill="#5e2129 "/>
                     <rect x="62.3154" y="-190.173" width="543" height="77.5714" rx="38.7857" transform="rotate(45 62.3154 -190.173)" fill="#5e2129 "/>
                     </g>
                  </svg>
               </div>
            </div>
            <div class="col-md-5 d-md-block d-none bg-primary p-0 mt-n1 h-100 overflow-hidden">
               <img src="<?= media() ?>/images/bg.jpg" class="img-fluid gradient-main animated-scaleX" alt="images">
            </div>
         </div>
      </section>
      </div>
    
    <!-- Library Bundle Script -->
    <script src="<?= media() ?>/js/core/libs.min.js"></script>
    
    <!-- External Library Bundle Script -->
    <script src="<?= media() ?>/js/core/external.min.js"></script>
    
    <!-- Widgetchart Script -->
    <script src="<?= media() ?>/js/charts/widgetcharts.js"></script>
    
    <!-- mapchart Script -->
    <script src="<?= media() ?>/js/charts/vectore-chart.js"></script>
    <script src="<?= media() ?>/js/charts/dashboard.js" ></script>
    
    <!-- fslightbox Script -->
    <script src="<?= media() ?>/js/plugins/fslightbox.js"></script>
    
    <!-- Settings Script -->
    <script src="<?= media() ?>/js/plugins/setting.js"></script>
    
    <!-- Slider-tab Script -->
    <script src="<?= media() ?>/js/plugins/slider-tabs.js"></script>
    
    <!-- Form Wizard Script -->
    <script src="<?= media() ?>/js/plugins/form-wizard.js"></script>
    
    <!-- AOS Animation Plugin-->
    
    <!-- App Script -->
    <script src="<?= media() ?>/js/hope-ui.js" defer></script>
    
    
  </body>
</html>