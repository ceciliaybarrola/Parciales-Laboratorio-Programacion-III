<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <meta http-equiv="Content-Script-Type" content="text/javascript" />


    <link rel="stylesheet" href="./bower_components/bootstrap/dist/css/bootstrap.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <script type="text/javascript" src="./bower_components/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="./bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    
    <script type="text/javascript" src="./scripts/principal.js"></script>

    <title>Principal</title>
</head>
<body style="background-color: lightskyblue;">
 
    <nav class="navbar navbar-expand-md bg-dark navbar-dark fixed-top">

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul id="main-nav" class="navbar-nav">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                    Listados
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#" onclick="SegundoParcial.Principal.MostrarUsuarios()">Usuarios</a>
                    <a class="dropdown-item" href="#" onclick="SegundoParcial.Principal.MostrarAutos()">Autos</a>
                </div>
              </li>

                <li class="dropdown dropdown">
                    <a class="nav-link " href="#" id="navbardrop" onclick="SegundoParcial.Principal.CrearForm('Alta')">
                    Alta Autos
                    </a>
                </li>
            
                <!-- <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                        Filtrados
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#" onclick="SegundoParcial.Principal.FiltrarPrecios()">Filtrar por Precio-rojo</a>
                        <a class="dropdown-item" href="#" onclick="SegundoParcial.Principal.FiltrarPromedio()">Filtrar por Promedio-marcas</a>
                        <a class="dropdown-item" href="#" onclick="SegundoParcial.Principal.FiltrarEmpleados()">Filtrar Empleados-superv</a>
                    </div>
                </li> -->

            </ul>
        </div>
    </nav>

    
       
<div class="container" style="margin-top: 100px;">

    <div class="alert alert-danger  hide col-12" id="error"></div>
    <div class="alert alert-warning hide col-12" id="warning"></div>
    <div class="alert alert-success  hide col-12" id="exito"></div>
    <div class="alert alert-info  hide col-12" id="info"></div>

    <div class="row">
        <div class="col bg-danger">
          <h6>IZQUIERDA</h6>
          <div style="height: auto;" id="divAutos"></div>
        </div>

        <div class="col bg-success w-100">
          <h6>DERECHA</h6>
          <div style="height: auto; " id="divUsuarios"></div>
        </div>
    </div>
    
  <!-- <div class="col-md-6" id="divAutos" style="float:left;background-color: red;"></div>
  <div class="col-md-6" id="divUsuarios" style="float:right;background-color: green;"></div> -->
  
</div> 
<div class="hidden" id="divHidden">0</div>
</body>
</html>