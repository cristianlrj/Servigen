<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $data['title'] ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { 
            background-color: #f8f9fa; 
        }
        .container-fluid { 
            max-width: 700px; /* Centra el contenido en pantallas grandes */
        }
        .card { 
            margin-top: 50px; 
            border: 0; /* Quitamos el borde feo */
            box-shadow: 0 10px 25px rgba(0,0,0,0.05); /* Sombra suave */
        }
        
        /* --- ESTILOS PARA LA NUEVA CALIFICACIÓN --- */

        /* Ocultamos el botón de radio real */
        .rating-group .form-check-input {
            display: none;
        }

        .rating-group .form-check-label {
            cursor: pointer;
            padding: 15px;
            border-radius: 10px;
            transition: all 0.2s ease-in-out;
            text-align: center;
            border: 2px solid transparent;
        }

        /* Efecto al pasar el mouse */
        .rating-group .form-check-label:hover {
            background-color: #f4f4f4;
            transform: scale(1.05);
        }

        /* Estilo cuando un ítem es seleccionado */
        .rating-group .form-check-input:checked + .form-check-label {
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        /* Colores específicos para cada opción */
        #satisfaccion5:checked + label { color: #198754; border-color: #198754; background-color: #e8f5e9; }
        #satisfaccion4:checked + label { color: #5cb85c; border-color: #5cb85c; background-color: #efffee; }
        #satisfaccion3:checked + label { color: #ffc107; border-color: #ffc107; background-color: #fff8e1; }
        #satisfaccion2:checked + label { color: #fd7e14; border-color: #fd7e14; background-color: #fff3e0; }
        #satisfaccion1:checked + label { color: #dc3545; border-color: #dc3545; background-color: #fdebee; }

        /* Iconos más grandes */
        .rating-group .bi {
            font-size: 2.5rem; /* 40px */
            line-height: 1;
        }
    </style>
</head>
<body>
<center>
        <img src="<?= base_url() ?>/public/assets/images/cintillo.jpeg" class="cintillo-login">
</center>
<div class="container-fluid content-inner py-0 pt-3">
 <div class="row mt-4">
  <div class="col-sm-12">
   <div class="card">
    <div class="card-header d-flex justify-content-between bg-white border-0 pt-4 px-4">
     <div class="header-title">
      <h4 class="card-title">Encuesta de Satisfacción</h4>
      <h6 class="card-subtitle text-muted">Reporte #<?= htmlspecialchars($data['falla']->getId()) ?></h6>
     </div>
    </div>
    <div class="card-body p-4 p-md-5">
     <p class="lead mb-4">Por favor, califique el servicio recibido para la falla: <br><strong>"<?= htmlspecialchars($data['falla']->getDescripcion()) ?>"</strong>.</p>

     <form action="<?= base_url() ?>/satisfaccion/guardarSatisfaccion" method="POST">
      <input type="hidden" name="umypf_n" value="<?= htmlspecialchars($data['falla']->getId()) ?>">
      <input type="hidden" name="token" value="<?= htmlspecialchars($data['token']) ?>">
      
      <div class="form-group mb-4">
        <label class="form-label fs-5"><strong>Nivel de Satisfacción:</strong></label>
        
        <div class="rating-group row row-cols-3 row-cols-sm-5 g-2">
            
            <div class="col form-check">
                <input class="form-check-input" type="radio" name="satisfaccion" id="satisfaccion1" value="1">
                <label class="form-check-label" for="satisfaccion1">
                    <i class="bi bi-emoji-angry-fill"></i>
                    <span class="d-block mt-2">Malo</span>
                </label>
            </div>

            <div class="col form-check">
                <input class="form-check-input" type="radio" name="satisfaccion" id="satisfaccion2" value="2">
                <label class="form-check-label" for="satisfaccion2">
                    <i class="bi bi-emoji-frown-fill"></i>
                    <span class="d-block mt-2">Regular</span>
                </label>
            </div>

            <div class="col form-check">
                <input class="form-check-input" type="radio" name="satisfaccion" id="satisfaccion3" value="3">
                <label class="form-check-label" for="satisfaccion3">
                    <i class="bi bi-emoji-neutral-fill"></i>
                    <span class="d-block mt-2">Bueno</span>
                    </label>
                </div>
            
            <div class="col form-check">
                <input class="form-check-input" type="radio" name="satisfaccion" id="satisfaccion4" value="4">
                <label class="form-check-label" for="satisfaccion4">
                    <i class="bi bi-emoji-smile-fill"></i>
                    <span class="d-block mt-2">Muy Bueno</span>
                </label>
            </div>

                <div class="col form-check">
                    <input class="form-check-input" type="radio" name="satisfaccion" id="satisfaccion5" value="5" required>
                    <label class="form-check-label" for="satisfaccion5">
                        <i class="bi bi-emoji-laughing-fill"></i>
                        <span class="d-block mt-2">Excelente</span>
                    </label>
                </div>
            
          


        </div>
      </div>

      <div class="form-group mb-4">
       <label for="comentarios" class="form-label fs-5"><strong>Comentarios Adicionales (Opcional)</strong></label>
       <textarea class="form-control" id="comentarios" name="comentarios" rows="4" placeholder="¿Hay algo más que quisiera compartir?"></textarea>
      </div>

      <div class="d-grid mt-5">
       <button type="submit" class="btn btn-primary btn-lg">
        <i class="bi bi-send-fill me-2"></i>Enviar Calificación
       </button>
      </div>
     </form>
    </div>
   </div>
  </div>
 </div>
</div>
</body>
</html>