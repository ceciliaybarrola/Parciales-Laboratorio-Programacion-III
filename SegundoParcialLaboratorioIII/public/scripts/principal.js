"use strict";
/// <reference path="../node_modules/@types/jquery/index.d.ts" />
var APIREST = "http://api_slim4/";
var SegundoParcial;
(function (SegundoParcial) {
    var Principal = /** @class */ (function () {
        function Principal() {
        }
        Principal.LimpiarDivs = function () {
            $("#error").html("");
            $("#warning").html("");
            $("#exito").html("");
            $("#info").html("");
            $("#error").addClass("hide");
            $("#warning").addClass("hide");
            $("#exito").addClass("hide");
            $("#info").addClass("hide");
        };
        Principal.MostrarUsuarios = function () {
            $.ajax({
                type: 'GET',
                url: APIREST,
                async: true
            })
                .done(function (resultado) {
                console.log(resultado);
                if (resultado.exito) {
                    var tabla = SegundoParcial.Principal.ArmarListaUsuarios(resultado.dato);
                    $("#divUsuarios").html(tabla);
                }
            })
                .fail(function (jqXHR, textStatus, errorThrown) {
                var resultado = JSON.parse(jqXHR.responseText);
                console.log(resultado);
                if (!resultado.exito) {
                    if (resultado.status == 403) {
                        window.location.replace(APIREST + "front-end-login");
                    }
                    $("#error").removeClass("hide");
                    $("#error").html(resultado.mensaje);
                }
            });
        };
        Principal.ArmarListaUsuarios = function (lista) {
            var tabla = '<table class="table table-hover table-striped table-dark">';
            tabla += '<thead class="thead-light"><tr><th>Correo</th><th>Nombre</th><th>Apellido</th><th>Perfil</th><th>Foto</th></tr></thead>';
            if (lista === false) {
                tabla = '<tr><td>---</td><td>---</td><td>---</td><td>---</td><th>---</td></tr>';
                $('#error').removeClass("hide");
                var aux = void 0;
                aux = document.getElementById("error");
                if (aux != null)
                    aux.innerHTML = "No se puedo acceder a la tabla de usuarios!" + '<button type="button" class="close" onclick="SegundoParcial.Principal.LimpiarDivs()">&times;</button>';
            }
            else {
                console.log(lista);
                lista.forEach(function (usuario) {
                    tabla += "<tr><td>" + usuario.correo + "</td><td>" + usuario.nombre + "</td><td>" + usuario.apellido + "</td><td>" + usuario.perfil + "</td>" +
                        "<td>";
                    if (usuario.foto !== null) {
                        tabla += "<img style='width: 50px; height: 50px;' src='../src/" + usuario.foto + "'>";
                    }
                    tabla += "</td></tr>";
                });
            }
            tabla += "</table>";
            return tabla;
        };
        Principal.MostrarAutos = function () {
            $.ajax({
                type: 'GET',
                url: APIREST + "autos",
                async: true
            })
                .done(function (resultado) {
                console.log(resultado);
                if (resultado.exito) {
                    var tabla = SegundoParcial.Principal.ArmarListaAutos(resultado.dato);
                    $("#divAutos").html(tabla);
                }
            })
                .fail(function (jqXHR, textStatus, errorThrown) {
                var resultado = JSON.parse(jqXHR.responseText);
                console.log(resultado);
                if (!resultado.exito) {
                    $("#error").removeClass("hide");
                    $("#error").html(resultado.mensaje);
                }
            });
        };
        Principal.ArmarListaAutos = function (lista) {
            var tabla = '<table class="table table-hover table-striped table-light">';
            tabla += '<thead class="thead-dark"><tr><th>Color</th><th>Marca</th><th>Precio</th><th>Modelo</th><th>Eliminar</th><th>Modificar</th></tr></thead>';
            if (lista === false) {
                tabla += '<tr><td>---</td><td>---</td><td>---</td><td>---</td></tr>';
                $('#error').removeClass("hide");
                var aux = void 0;
                aux = document.getElementById("error");
                if (aux != null)
                    aux.innerHTML = "No se puedo acceder a la tabla de autos!" + '<button type="button" class="close" onclick="SegundoParcial.Principal.LimpiarDivs()">&times;</button>';
            }
            else {
                lista.forEach(function (auto) {
                    tabla += "<tr><td>" + auto.color + "</td><td>" + auto.marca + "</td><td>" + auto.precio + "</td><td>" + auto.modelo + "</td>" +
                        "<td>" + "<button class='btn btn-danger' onclick=" + 'SegundoParcial.Principal.EliminarAuto(' + JSON.stringify(auto) + ')' + ">Borrar</button></td>" + "<td><button class='btn btn-info' onclick=" + 'SegundoParcial.Principal.ModificarAuto(' + JSON.stringify(auto) + ')' + '>Modificar</button>' + "</td></tr>";
                });
            }
            tabla += "</table>";
            return tabla;
        };
        Principal.CrearForm = function (metodo) {
            SegundoParcial.Principal.LimpiarDivs();
            var form = '<form action="" id="loginForm" method="post" class="well form-horizontal col-md-6" style="background-color:darkcyan;">' +
                '<div class="form-group">' +
                '<div class="col-md-12 inputGroupContainer">' +
                '<div class="input-group">' +
                '<span class="input-group-addon"><i class="fas fa-trademark"></i></span>' +
                '<input type="text" name="marca" id="marca" class="form-control" placeholder="Mrca">' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<div class="col-md-12 inputGroupContainer">' +
                '<div class="input-group">' +
                '<span class="input-group-addon"><i class="fas fa-palette"></i></span>' +
                '<input type="text" name="color" id="color" class="form-control" placeholder="Color">' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<div class="col-md-12 inputGroupContainer">' +
                '<div class="input-group">' +
                '<span class="input-group-addon"><i class="fas fa-car"></i></span>' +
                '<input type="text" name="modelo" id="modelo" class="form-control" placeholder="Modelo">' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<div class="col-md-12 inputGroupContainer">' +
                '<div class="input-group">' +
                '<span class="input-group-addon"><i class="fas fa-dollar-sign"></i></span>' +
                '<input type="text" name="precio" id="precio" class="form-control" placeholder="Precio">' +
                '</div>' +
                '</div>' +
                '</div>';
            if (metodo == "Modificar") {
                form += '<div class="form-group">' +
                    '<label class="control-label col-md-1"></label>' +
                    '<button class="btn btn-success col-md-4" type="button" id="btnEnviar" onclick="SegundoParcial.Principal.Modificar()">' +
                    'Modificar' +
                    '</button>' +
                    '<label class="control-label col-md-1"></label>' +
                    '<button class="btn btn-warning col-md-4" type="reset">' +
                    'Limpiar' +
                    '</button>';
            }
            else {
                form += '<div class="form-group">' +
                    '<label class="control-label col-md-1"></label>' +
                    '<button class="btn btn-success col-md-4" type="button" id="btnEnviar" onclick="SegundoParcial.Principal.AltaAuto()">' +
                    'Agregar' +
                    '</button>' +
                    '<label class="control-label col-md-1"></label>' +
                    '<button class="btn btn-warning col-md-4" type="reset">' +
                    'Limpiar' +
                    '</button>';
            }
            form += '</div>' +
                '</form>';
            $("#divAutos").html(form);
        };
        Principal.EliminarAuto = function (auto) {
            SegundoParcial.Principal.LimpiarDivs();
            var confirmar = confirm("Confirma para eliminar el auto:\n    modelo: " + auto.modelo + "\n    color: " + auto.color + "\n    marca: " + auto.marca);
            if (confirmar) {
                var token = localStorage.getItem('jwt');
                $.ajax({
                    type: 'DELETE',
                    url: APIREST + "cars/" + auto.id,
                    dataType: 'json',
                    data: JSON.stringify(auto.id),
                    async: true
                })
                    .done(function (resultado) {
                    console.log(resultado);
                    if (resultado.exito) {
                        SegundoParcial.Principal.MostrarAutos();
                    }
                })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText);
                    var resultado = JSON.parse(jqXHR.responseText);
                    if (!resultado.exito) {
                        $("#warning").removeClass("hide");
                        $("#warning").html(resultado.mensaje + '<button type="button" class="close" onclick="SegundoParcial.Principal.LimpiarDivs()">&times;</button>');
                    }
                });
            }
        };
        Principal.ModificarAuto = function (auto) {
            SegundoParcial.Principal.LimpiarDivs();
            SegundoParcial.Principal.CrearForm("Modificar");
            $("#marca").val(auto.marca);
            $("#color").val(auto.color);
            $("#modelo").val(auto.modelo);
            $("#precio").val(auto.precio);
            $("#divHidden").val(auto.id);
        };
        Principal.Modificar = function () {
            SegundoParcial.Principal.LimpiarDivs();
            var color = $("#color").val();
            var marca = $("#marca").val();
            var modelo = $("#modelo").val();
            var precio = $("#precio").val();
            var id = $("#divHidden").val();
            var auto = {};
            auto.id_auto = id;
            auto.color = color;
            auto.marca = marca;
            auto.precio = precio;
            auto.modelo = modelo;
            $.ajax({
                type: 'PUT',
                url: APIREST + "cars/" + JSON.stringify(auto),
                dataType: 'json',
                data: JSON.stringify(auto),
                async: true
            })
                .done(function (resultado) {
                console.log(resultado);
                if (resultado.exito) {
                    SegundoParcial.Principal.MostrarAutos();
                }
            })
                .fail(function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
                var resultado = JSON.parse(jqXHR.responseText);
                if (!resultado.exito) {
                    $("#warning").removeClass("hide");
                    $("#warning").html(resultado.mensaje + '<button type="button" class="close" onclick="SegundoParcial.Principal.LimpiarDivs()">&times;</button>');
                    SegundoParcial.Principal.MostrarAutos();
                }
            });
        };
        Principal.AltaAuto = function () {
            SegundoParcial.Principal.LimpiarDivs();
            var color = $("#color").val();
            var marca = $("#marca").val();
            var modelo = $("#modelo").val();
            var precio = $("#precio").val();
            var auto = {};
            auto.color = color;
            auto.marca = marca;
            auto.precio = precio;
            auto.modelo = modelo;
            var form = new FormData();
            form.append("auto", JSON.stringify(auto));
            $.ajax({
                type: 'POST',
                url: APIREST,
                dataType: 'json',
                contentType: false,
                processData: false,
                data: form,
                async: true
            })
                .done(function (resultado) {
                console.log(resultado);
                if (resultado.Exito) {
                    $("#exito").removeClass("hide");
                    $("#exito").html(resultado.Mensaje + '<button type="button" class="close" onclick="SegundoParcial.Principal.LimpiarDivs()">&times;</button>');
                }
            })
                .fail(function (jqXHR, textStatus, errorThrown) {
                var resultado = JSON.parse(jqXHR.responseText);
                console.log(resultado);
                if (!resultado.Exito) {
                    $("#error").removeClass("hide");
                    $("#error").html(resultado.Mensaje + '<button type="button" class="close" onclick="SegundoParcial.Principal.LimpiarDivs()">&times;</button>');
                }
            });
        };
        Principal.FiltrarPrecios = function () {
            SegundoParcial.Principal.LimpiarDivs();
            $.ajax({
                type: 'GET',
                url: APIREST + "autos/",
                async: true
            })
                .done(function (resultado) {
                console.log(resultado);
                if (resultado.exito) {
                    var aux = ((resultado.datos)).filter(function (auto, index, array) { return (auto.precio > 199999 && auto.color != "rojo"); });
                    var tabla = SegundoParcial.Principal.ArmarListaAutos(aux);
                    $("#divUsuarios").html(tabla);
                }
            })
                .fail(function (jqXHR, textStatus, errorThrown) {
                var resultado = JSON.parse(jqXHR.responseText);
                console.log(resultado);
                if (!resultado.exito) {
                    $("#error").removeClass("hide");
                    $("#error").html(resultado.mensaje + '<button type="button" class="close" onclick="SegundoParcial.Principal.LimpiarDivs()">&times;</button>');
                }
            });
        };
        Principal.FiltrarPromedio = function () {
            SegundoParcial.Principal.LimpiarDivs();
            $.ajax({
                type: 'GET',
                url: APIREST + "autos/",
                async: true
            })
                .done(function (resultado) {
                console.log(resultado);
                if (resultado.exito) {
                    var autosFiltrados = ((resultado.datos)).filter(function (auto, index, array) { return (auto.marca.charAt(0) == "F" || auto.marca.charAt(0) == "f"); });
                    var promedioPrecio = 0;
                    if (autosFiltrados.length > 0) {
                        promedioPrecio = ((autosFiltrados)).reduce(function (anterior, actual, index, array) {
                            return anterior + parseFloat(actual.precio);
                        }, 0) / autosFiltrados.length;
                    }
                    var retorno = promedioPrecio.toFixed(2);
                    $("#info").html(retorno + '<button type="button" class="close" onclick="SegundoParcial.Principal.LimpiarDivs()">&times;</button>');
                    $('#info').removeClass("hide");
                }
            })
                .fail(function (jqXHR, textStatus, errorThrown) {
                var resultado = JSON.parse(jqXHR.responseText);
                console.log(resultado);
                if (!resultado.exito) {
                    if (resultado.status == 403) {
                        window.location.replace(APIREST + "front-end-login");
                    }
                    $("#error").removeClass("hide");
                    $("#error").html(resultado.mensaje + '<button type="button" class="close" onclick="SegundoParcial.Principal.LimpiarDivs()">&times;</button>');
                }
            });
        };
        Principal.FiltrarEmpleados = function () {
            SegundoParcial.Principal.LimpiarDivs();
            $.ajax({
                type: 'GET',
                url: APIREST,
                async: true
            })
                .done(function (resultado) {
                console.log(resultado);
                if (resultado.exito) {
                    var aux = ((resultado.datos)).filter(function (user, index, array) { return user.perfil.toLowerCase() == "empleado" || user.perfil.toLowerCase() == "supervisor"; });
                    var tabla = SegundoParcial.Principal.ArmarListaUsuarios(aux);
                    $("#divAutos").html(tabla);
                }
            })
                .fail(function (jqXHR, textStatus, errorThrown) {
                var resultado = JSON.parse(jqXHR.responseText);
                console.log(resultado);
                if (!resultado.exito) {
                    if (resultado.status == 403) {
                        window.location.replace(APIREST + "front-end-login");
                    }
                    $("#error").removeClass("hide");
                    $("#error").html(resultado.mensaje + '<button type="button" class="close" onclick="SegundoParcial.Principal.LimpiarDivs()">&times;</button>');
                }
            });
        };
        return Principal;
    }());
    SegundoParcial.Principal = Principal;
})(SegundoParcial || (SegundoParcial = {}));
//# sourceMappingURL=principal.js.map