/// <reference path="../node_modules/@types/jquery/index.d.ts" />
const APIREST:string = "http://api_slim4/";
namespace SegundoParcial{

    export class Principal
    {
        public static LimpiarDivs()
        {
            $("#error").html("");
            $("#warning").html("");
            $("#exito").html("");
            $("#info").html("");

            $("#error").addClass("hide");
            $("#warning").addClass("hide");
            $("#exito").addClass("hide");
            $("#info").addClass("hide");
        }
        public static MostrarUsuarios()
        {
            $.ajax({
                type: 'GET',
                url: APIREST,
                async: true
            }
            )
            .done(function (resultado:any){
                console.log(resultado);
                if(resultado.exito)
                {
                    let tabla = SegundoParcial.Principal.ArmarListaUsuarios(resultado.dato);
                    $("#divUsuarios").html(tabla);
                }

            })
            .fail(function (jqXHR:any, textStatus:any, errorThrown:any){
                let resultado = JSON.parse(jqXHR.responseText);
                console.log(resultado);
                
                if(!resultado.exito)
                {
                    if(resultado.status == 403)
                    {
                        window.location.replace(APIREST+"front-end-login");
                    }
                    $("#error").removeClass("hide");
                    $("#error").html(resultado.mensaje);
                }
                
            });
        }

        public static ArmarListaUsuarios(lista:any)
        {
            let tabla:string = '<table class="table table-hover table-striped table-dark">';
            tabla += '<thead class="thead-light"><tr><th>Correo</th><th>Nombre</th><th>Apellido</th><th>Perfil</th><th>Foto</th></tr></thead>';

            if(lista === false)
            {
                tabla = '<tr><td>---</td><td>---</td><td>---</td><td>---</td><th>---</td></tr>';
                $('#error').removeClass("hide");
                    let aux;
                    aux=document.getElementById("error");
                    if(aux!=null)
                        aux.innerHTML = "No se puedo acceder a la tabla de usuarios!"+'<button type="button" class="close" onclick="SegundoParcial.Principal.LimpiarDivs()">&times;</button>';
            }
            else
            {
                console.log(lista);
                lista.forEach((usuario:any) => {

                    tabla += "<tr><td>"+usuario.correo+"</td><td>"+usuario.nombre+"</td><td>"+usuario.apellido+"</td><td>"+usuario.perfil+"</td>"+
                    "<td>";
                    if(usuario.foto!==null)
                    {
                        tabla+="<img style='width: 50px; height: 50px;' src='../src/"+usuario.foto+"'>";
                    }
                    tabla+="</td></tr>";
                });
            }

            tabla += "</table>";

            return tabla;
        }

        public static MostrarAutos()
        {

            $.ajax({
                type: 'GET',
                url: APIREST+"autos",
                async: true
            }
            )
            .done(function (resultado:any){
                console.log(resultado);
                
                if(resultado.exito)
                {
                    let tabla = SegundoParcial.Principal.ArmarListaAutos(resultado.dato);
                    $("#divAutos").html(tabla);
                }

            })
            .fail(function (jqXHR:any, textStatus:any, errorThrown:any){
                let resultado = JSON.parse(jqXHR.responseText);
                console.log(resultado);
                
                if(!resultado.exito)
                {
                    $("#error").removeClass("hide");
                    $("#error").html(resultado.mensaje);
                }
                
            });
        }

        public static ArmarListaAutos(lista:any)
        {

            let tabla:string = '<table class="table table-hover table-striped table-light">';
            tabla += '<thead class="thead-dark"><tr><th>Color</th><th>Marca</th><th>Precio</th><th>Modelo</th><th>Eliminar</th><th>Modificar</th></tr></thead>';

            if(lista === false)
            {
                tabla += '<tr><td>---</td><td>---</td><td>---</td><td>---</td></tr>';
                $('#error').removeClass("hide");
                    let aux;
                    aux=document.getElementById("error");
                    if(aux!=null)
                        aux.innerHTML = "No se puedo acceder a la tabla de autos!"+'<button type="button" class="close" onclick="SegundoParcial.Principal.LimpiarDivs()">&times;</button>';
            }
            else
            {
                lista.forEach((auto:any) => {

                    tabla += "<tr><td>"+auto.color+"</td><td>"+auto.marca+"</td><td>"+auto.precio+"</td><td>"+auto.modelo+"</td>"+
                    "<td>"+"<button class='btn btn-danger' onclick="+'SegundoParcial.Principal.EliminarAuto('+JSON.stringify(auto)+')'+">Borrar</button></td>"+"<td><button class='btn btn-info' onclick="+'SegundoParcial.Principal.ModificarAuto('+ JSON.stringify(auto) +')'+'>Modificar</button>'+"</td></tr>";
                });
            }

            tabla += "</table>";

            return tabla;
        }

        public static CrearForm(metodo : any)
        {
            SegundoParcial.Principal.LimpiarDivs();
            let form:any='<form action="" id="loginForm" method="post" class="well form-horizontal col-md-6" style="background-color:darkcyan;">'+

            '<div class="form-group">'+
                '<div class="col-md-12 inputGroupContainer">'+
                    '<div class="input-group">'+
                        '<span class="input-group-addon"><i class="fas fa-trademark"></i></span>'+
                        '<input type="text" name="marca" id="marca" class="form-control" placeholder="Mrca">'+
                    '</div>'+
                '</div>'+
            '</div>'+
        
            '<div class="form-group">'+
                '<div class="col-md-12 inputGroupContainer">'+
                    '<div class="input-group">'+
                        '<span class="input-group-addon"><i class="fas fa-palette"></i></span>'+
                        '<input type="text" name="color" id="color" class="form-control" placeholder="Color">'+
                    '</div>'+
                '</div>'+
            '</div>'+

            '<div class="form-group">'+
                '<div class="col-md-12 inputGroupContainer">'+
                    '<div class="input-group">'+
                        '<span class="input-group-addon"><i class="fas fa-car"></i></span>'+
                        '<input type="text" name="modelo" id="modelo" class="form-control" placeholder="Modelo">'+
                    '</div>'+
                '</div>'+
            '</div>'+
        
            '<div class="form-group">'+
                '<div class="col-md-12 inputGroupContainer">'+
                    '<div class="input-group">'+
                        '<span class="input-group-addon"><i class="fas fa-dollar-sign"></i></span>'+
                        '<input type="text" name="precio" id="precio" class="form-control" placeholder="Precio">'+
                    '</div>'+
                '</div>'+
            '</div>';
        
            if(metodo == "Modificar")
            {
                form+='<div class="form-group">'+
                    '<label class="control-label col-md-1"></label>'+
                    '<button class="btn btn-success col-md-4" type="button" id="btnEnviar" onclick="SegundoParcial.Principal.Modificar()">'+
                        'Modificar'+
                    '</button>'+
                    '<label class="control-label col-md-1"></label>'+
                    '<button class="btn btn-warning col-md-4" type="reset">'+
                        'Limpiar'+
                    '</button>';
            }
            else
            {
                form+='<div class="form-group">'+
                    '<label class="control-label col-md-1"></label>'+
                    '<button class="btn btn-success col-md-4" type="button" id="btnEnviar" onclick="SegundoParcial.Principal.AltaAuto()">'+
                        'Agregar'+
                    '</button>'+
                    '<label class="control-label col-md-1"></label>'+
                    '<button class="btn btn-warning col-md-4" type="reset">'+
                        'Limpiar'+
                    '</button>';
            }
            
            form+='</div>'+
            '</form>';
        
            $("#divAutos").html(form);
        }

        public static EliminarAuto(auto : any)
        {
            SegundoParcial.Principal.LimpiarDivs();

            let confirmar = confirm("Confirma para eliminar el auto:\n    modelo: "+auto.modelo+"\n    color: "+auto.color+"\n    marca: "+auto.marca);
            if (confirmar) {
                let token : any = localStorage.getItem('jwt');
                $.ajax({
                    type: 'DELETE',
                    url: APIREST + "cars/" + auto.id,
                    dataType:'json',
                    data: JSON.stringify(auto.id),
                    async: true
                }
                )
                .done(function (resultado:any){
                    console.log(resultado);
                
                    if(resultado.exito)
                    {
                        SegundoParcial.Principal.MostrarAutos();
                    }

                })
                .fail(function (jqXHR:any, textStatus:any, errorThrown:any){
                    console.log(jqXHR.responseText);
                    let resultado = JSON.parse(jqXHR.responseText);
                
                    if(!resultado.exito)
                    {
                        $("#warning").removeClass("hide");
                        $("#warning").html(resultado.mensaje+'<button type="button" class="close" onclick="SegundoParcial.Principal.LimpiarDivs()">&times;</button>');
                    }
                
                });
            }
        }

        public static ModificarAuto(auto : any)
        {
            SegundoParcial.Principal.LimpiarDivs();

            SegundoParcial.Principal.CrearForm("Modificar");
            $("#marca").val(auto.marca);
            $("#color").val(auto.color);
            $("#modelo").val(auto.modelo);
            $("#precio").val(auto.precio);
            $("#divHidden").val(auto.id);

        }

        public static Modificar()
        {
            SegundoParcial.Principal.LimpiarDivs();
            let color = $("#color").val();
            let marca = $("#marca").val();
            let modelo = $("#modelo").val();
            let precio = $("#precio").val();
            let id = $("#divHidden").val();
            
            let auto:any = {}; 
            auto.id_auto = id;
            auto.color = color;
            auto.marca = marca;
            auto.precio = precio;
            auto.modelo = modelo;

            $.ajax({
                type: 'PUT',
                url: APIREST+ "cars/"+JSON.stringify(auto),
                dataType:'json',
                data: JSON.stringify(auto),
                async: true
            }
            )
            .done(function (resultado:any){
                console.log(resultado);
                
                if(resultado.exito)
                {
                    SegundoParcial.Principal.MostrarAutos();
                }

            })
            .fail(function (jqXHR:any, textStatus:any, errorThrown:any){
                console.log(jqXHR.responseText);
                let resultado = JSON.parse(jqXHR.responseText);
                
                if(!resultado.exito)
                {

                    $("#warning").removeClass("hide");
                    $("#warning").html(resultado.mensaje+'<button type="button" class="close" onclick="SegundoParcial.Principal.LimpiarDivs()">&times;</button>');
                    SegundoParcial.Principal.MostrarAutos();
                }
                
            });
        }

        public static AltaAuto()
        {
            SegundoParcial.Principal.LimpiarDivs();

            let color = $("#color").val();
            let marca = $("#marca").val();
            let modelo = $("#modelo").val();
            let precio = $("#precio").val();
            
            let auto:any = {}; 
            auto.color = color;
            auto.marca = marca;
            auto.precio = precio;
            auto.modelo = modelo;

            let form = new FormData();
            form.append("auto", JSON.stringify(auto));

            $.ajax({
                type: 'POST',
                url: APIREST,
                dataType:'json',
                contentType: false,
                processData: false,
                data: form,
                async: true
            }
            )
            .done(function (resultado:any){
                console.log(resultado);

                if(resultado.Exito)
                {
                    $("#exito").removeClass("hide");
                    $("#exito").html(resultado.Mensaje+'<button type="button" class="close" onclick="SegundoParcial.Principal.LimpiarDivs()">&times;</button>');
                }

            })
            .fail(function (jqXHR:any, textStatus:any, errorThrown:any){
                let resultado = JSON.parse(jqXHR.responseText);
                console.log(resultado);
                
                if(!resultado.Exito)
                {
                    $("#error").removeClass("hide");
                    $("#error").html(resultado.Mensaje+'<button type="button" class="close" onclick="SegundoParcial.Principal.LimpiarDivs()">&times;</button>');
                }
                
            });
        }

        public static FiltrarPrecios()
        {
            SegundoParcial.Principal.LimpiarDivs();

            $.ajax({
                type: 'GET',
                url: APIREST+"autos/",
                async: true
            }
            )
            .done(function (resultado:any){
                console.log(resultado);
                
                if(resultado.exito)
                {
                    let aux = ((resultado.datos)).filter((auto:any, index:any, array:any) => (auto.precio > 199999 && auto.color != "rojo"));
                    let tabla = SegundoParcial.Principal.ArmarListaAutos(aux);
                    $("#divUsuarios").html(tabla);
                }

            })
            .fail(function (jqXHR:any, textStatus:any, errorThrown:any){
                let resultado = JSON.parse(jqXHR.responseText);
                console.log(resultado);
                
                if(!resultado.exito)
                {
                    $("#error").removeClass("hide");
                    $("#error").html(resultado.mensaje+'<button type="button" class="close" onclick="SegundoParcial.Principal.LimpiarDivs()">&times;</button>');
                }
                
            });
        }

        public static FiltrarPromedio()
        {
            SegundoParcial.Principal.LimpiarDivs();

            $.ajax({
                type: 'GET',
                url: APIREST+"autos/",
                async: true
            }
            )
            .done(function (resultado:any){
                console.log(resultado);
                
                if(resultado.exito)
                {
                    let autosFiltrados = ((resultado.datos)).filter((auto:any, index:any, array:any) => (auto.marca.charAt(0) == "F" || auto.marca.charAt(0) == "f"));
                    let promedioPrecio = 0;
                    if(autosFiltrados.length > 0)
                    {
                        promedioPrecio = ((autosFiltrados)).reduce((anterior:any, actual:any, index:any, array:any) => {
                        return anterior + parseFloat(actual.precio);
                        }, 0) / autosFiltrados.length;
                        
                    }
                    let retorno =  promedioPrecio.toFixed(2);
                    
                    $("#info").html(retorno+'<button type="button" class="close" onclick="SegundoParcial.Principal.LimpiarDivs()">&times;</button>');   
                    $('#info').removeClass("hide");
                }

            })
            .fail(function (jqXHR:any, textStatus:any, errorThrown:any){
                let resultado = JSON.parse(jqXHR.responseText);
                console.log(resultado);
                
                if(!resultado.exito)
                {
                    if(resultado.status == 403)
                    {
                        window.location.replace(APIREST+"front-end-login");
                    }
                    $("#error").removeClass("hide");
                    $("#error").html(resultado.mensaje+'<button type="button" class="close" onclick="SegundoParcial.Principal.LimpiarDivs()">&times;</button>');
                }
                
            });
        }

        public static FiltrarEmpleados()
        {
            SegundoParcial.Principal.LimpiarDivs();
            
            $.ajax({
                type: 'GET',
                url: APIREST,
                async: true
            }
            )
            .done(function (resultado:any){
                console.log(resultado);
                
                if(resultado.exito)
                {
                    let aux = ((resultado.datos)).filter((user:any, index:any, array:any) => user.perfil.toLowerCase() == "empleado" || user.perfil.toLowerCase() == "supervisor");
                    
                    let tabla = SegundoParcial.Principal.ArmarListaUsuarios(aux);
                    $("#divAutos").html(tabla);
                }

            })
            .fail(function (jqXHR:any, textStatus:any, errorThrown:any){
                let resultado = JSON.parse(jqXHR.responseText);
                console.log(resultado);
                
                if(!resultado.exito)
                {
                    if(resultado.status == 403)
                    {
                        window.location.replace(APIREST+"front-end-login");
                    }
                    $("#error").removeClass("hide");
                    $("#error").html(resultado.mensaje+'<button type="button" class="close" onclick="SegundoParcial.Principal.LimpiarDivs()">&times;</button>');
                }
                
            });
        }

    }
}