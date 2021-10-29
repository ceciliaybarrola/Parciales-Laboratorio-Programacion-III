"use strict";
/// <reference path="./node_modules/@types/jquery/index.d.ts" />
/// <reference path="./Iparte2.ts" />
/// <reference path="./IParte3.ts" />
var RecuperatorioPrimerParcial;
(function (RecuperatorioPrimerParcial) {
    var Manejadora = /** @class */ (function () {
        function Manejadora() {
        }
        Manejadora.AgregarCocinero = function () {
            var especialidad = document.getElementById("especialidad").value;
            var email = document.getElementById("correo").value;
            var clave = document.getElementById("clave").value;
            var pagina = "./BACKEND/AltaCocinero.php";
            var form = new FormData();
            form.append("especialidad", especialidad);
            form.append("email", email);
            form.append("clave", clave);
            $.ajax({
                url: pagina,
                type: 'POST',
                dataType: "json",
                contentType: false,
                processData: false,
                data: form,
                async: true,
            }).done(function (retJSON) {
                if (retJSON.exito) {
                    console.log("Agregado Correcamente");
                    console.log(retJSON.mensaje);
                    alert("Agregado Correctamente");
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert(jqXHR.responseText + "\n" + textStatus + "\n" + errorThrown);
            });
        };
        Manejadora.MostrarCocineros = function () {
            $.ajax({
                url: './BACKEND/ListadoCocineros.php',
                type: 'GET',
                cache: false,
                processData: false
            }).done(function (retJSON) {
                var arrayJson = JSON.parse(retJSON);
                var tabla = "";
                tabla += "<table border=1 style='width:100%' text-aling='center'> <thead>";
                tabla += "<tr>";
                tabla += "<th>Especialidad</th>";
                tabla += "<th>Correo</th>";
                tabla += "<th>Clave</th>";
                tabla += "</tr> </thead>";
                for (var i = 0; i < arrayJson.length; i++) {
                    tabla += "<tr>";
                    tabla += "<td>" + arrayJson[i]["especialidad"] + "</td>";
                    tabla += "<td>" + arrayJson[i]["email"] + "</td>";
                    tabla += "<td>" + arrayJson[i]["clave"] + "</td>";
                    console.log(arrayJson[i]);
                    tabla += "</tr>";
                }
                tabla += "</table>";
                $("#divTabla").html(tabla);
            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert(jqXHR.responseText + "\n" + textStatus + "\n" + errorThrown);
            });
        };
        Manejadora.VerificarExistencia = function () {
            var correo = document.getElementById("correo").value;
            var clave = document.getElementById("clave").value;
            $.ajax({
                url: './BACKEND/VerificarCocinero.php',
                type: 'POST',
                dataType: "html",
                data: { "email": correo, "clave": clave },
                async: true,
            }).done(function (retJSON) {
                var respuesta = JSON.parse(retJSON);
                if (JSON.stringify(respuesta.masPopulares)) {
                    alert(respuesta.mensaje + JSON.stringify(respuesta.masPopulares));
                }
                else {
                    alert(respuesta.mensaje);
                }
                console.log(respuesta);
            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert(jqXHR.responseText + "\n" + textStatus + "\n" + errorThrown);
            });
        };
        Manejadora.AgregarRecetaSinFoto = function () {
            var nombre = document.getElementById("nombre").value;
            var ingrediente = document.getElementById("ingredientes").value;
            var tipo = document.getElementById("cboTipo").value;
            var form = new FormData();
            form.append("nombre", nombre);
            form.append("ingredientes", ingrediente);
            form.append("tipo", tipo);
            $.ajax({
                url: './BACKEND/AgregarRecetaSinFoto.php',
                type: 'POST',
                dataType: "json",
                data: form,
                async: true,
                contentType: false,
                processData: false
            }).done(function (retJSON) {
                if (retJSON.exito) {
                    console.log("Agregado Correcamente");
                    console.log(retJSON.mensaje);
                    alert("Agregado Correctamente");
                    Manejadora.MostrarRecetas();
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert(jqXHR.responseText + "\n" + textStatus + "\n" + errorThrown);
            });
        };
        Manejadora.MostrarRecetas = function () {
            $.ajax({
                url: './BACKEND/ListadoRecetas.php',
                type: 'GET',
                cache: false,
                processData: false
            }).done(function (retJSON) {
                var lista = JSON.parse(retJSON);
                var tabla = "<table style=\"padding: 20px; margin: 0 auto; width: 900px; text-align: center;\"><tr>\n                    <td colspan=\"6\">\n                    <hr />\n                    </td>\n                    </tr>\n                    <tr>\n                        <td>Id</td>\n                        <td>Nombre</td>\n                        <td>Ingredientes</td>\n                        <td>Tipo</td>\n                        <td>Foto</td>\n                        <td>Acciones</td>\n                    </tr>";
                for (var i = 0; i < lista.length; i++) {
                    var element = lista[i];
                    tabla += "<tr>";
                    tabla += "<td>" + lista[i].id + "</td>";
                    tabla += "<td>" + lista[i].nombre + "</td>";
                    tabla += "<td>" + lista[i].ingredientes + "</td>";
                    tabla += "<td>" + lista[i].tipo + "</td>";
                    if (lista[i].pathFoto == undefined || lista[i].pathFoto == null || lista[i].pathFoto == "") {
                        tabla += "<td>SinFoto</td>";
                    }
                    else {
                        tabla += "<td><img src=\"" + lista[i].pathFoto + "\" height=\"40\" width=\"40\"></td>";
                    }
                    console.log(lista[i]);
                    var objJson = JSON.stringify(lista[i]);
                    console.log(objJson);
                    tabla += "<td><input type='button' onclick='new RecuperatorioPrimerParcial.Manejadora.btnModificar(" + objJson + ")' value='Modificar'</td>";
                    tabla += "<td><input type='button' onclick='new RecuperatorioPrimerParcial.Manejadora.btnEliminar(" + objJson + ")' value='Eliminar'><td>";
                }
                tabla += "</tr><tr><td colspan=\"6\"><hr /></td></tr>";
                $("#divTabla").html(tabla);
            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert(jqXHR.responseText + "\n" + textStatus + "\n" + errorThrown);
            });
        };
        Manejadora.Agregar = function () {
            var Manejadora = new RecuperatorioPrimerParcial.Manejadora();
            Manejadora.AgregarVerificarReceta();
        };
        Manejadora.prototype.AgregarVerificarReceta = function () {
            var xhr = new XMLHttpRequest();
            var id = document.getElementById("id_ciudad").value;
            var nombre = document.getElementById("nombre").value;
            var ingredientes = document.getElementById("ingredientes").value;
            var tipo = document.getElementById("cboTipo").value;
            var foto = document.getElementById("foto");
            var form = new FormData();
            if (document.getElementById("hdnIdModificacion").value == "true") {
                var json = { "id": id, "nombre": nombre, "ingredientes": ingredientes, "tipo": tipo, "foto": "" };
                console.log(JSON.stringify(json));
                form.append('receta_json', JSON.stringify(json));
                form.append('foto', foto.files[0]);
                $.ajax({
                    url: "./BACKEND/ModificarReceta.php",
                    type: 'POST',
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    data: form,
                    async: true
                }).done(function (retJSON) {
                    if (retJSON.exito) {
                        console.log("Modificado Correcamente");
                        console.log(retJSON.mensaje);
                        alert("Modificado Correctamente");
                        $("#hdnIdModificacion").val("false");
                        Manejadora.MostrarRecetas();
                    }
                    else {
                        alert(retJSON.mensaje);
                        console.log(retJSON.mensaje);
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    alert(jqXHR.responseText + "\n" + textStatus + "\n" + errorThrown);
                });
            }
            else {
                form.append('id', id);
                form.append('nombre', nombre);
                form.append('ingredientes', ingredientes);
                form.append('tipo', tipo);
                form.append('foto', foto.files[0]);
                $.ajax({
                    url: './BACKEND/AgregarReceta.php',
                    type: 'POST',
                    dataType: "json",
                    data: form,
                    contentType: false,
                    processData: false,
                    async: true
                }).done(function (retJSON) {
                    if (retJSON.exito) {
                        console.log("Agregado Correcamente");
                        console.log(retJSON.mensaje);
                        alert("Agregado Correctamente");
                        Manejadora.MostrarRecetas();
                    }
                    else {
                        alert(retJSON.mensaje);
                        console.log(retJSON.mensaje);
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    alert(jqXHR.responseText + "\n" + textStatus + "\n" + errorThrown);
                });
            }
        };
        Manejadora.btnEliminar = function (json) {
            var Manejadora = new RecuperatorioPrimerParcial.Manejadora();
            Manejadora.EliminarReceta(json);
        };
        Manejadora.prototype.EliminarReceta = function (json) {
            if (!confirm("Desea eliminar la receta seleccionada? nombre: " + json.nombre + " tipo: " + json.tipo))
                return;
            console.log(json);
            var form = new FormData();
            form.append('receta_json', JSON.stringify(json));
            form.append('accion', 'borrar');
            $.ajax({
                url: './BACKEND/EliminarReceta.php',
                type: 'POST',
                dataType: "json",
                contentType: false,
                data: form,
                processData: false,
                async: true
            }).done(function (retJSON) {
                if (retJSON.exito) {
                    console.log("Elminado Correcamente");
                    console.log(retJSON);
                    alert("Eliminado Correctamente");
                    Manejadora.MostrarRecetas();
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert(jqXHR.responseText + "\n" + textStatus + "\n" + errorThrown);
            });
        };
        Manejadora.btnModificar = function (json) {
            var Manejadora = new RecuperatorioPrimerParcial.Manejadora();
            Manejadora.ModificarReceta(json);
        };
        Manejadora.prototype.ModificarReceta = function (json) {
            console.log(json);
            document.getElementById("id_ciudad").value = json["id"];
            document.getElementById("id_ciudad").readOnly = true;
            document.getElementById("nombre").value = json["nombre"];
            document.getElementById("ingredientes").value = json["ingredientes"];
            document.getElementById("cboTipo").value = json["tipo"];
            if (json["pathFoto"] != null && json["pathFoto"] != undefined && json["pathFoto"] != "") {
                $("#imgFoto").attr("src", json["pathFoto"]);
            }
            $("#hdnIdModificacion").val("true");
        };
        Manejadora.Modificar = function () {
            var Manejadora = new RecuperatorioPrimerParcial.Manejadora();
            Manejadora.AgregarVerificarReceta();
            $('#id').prop('readonly', false);
            $("#id").val("");
            $("#nombre").val("");
            $("#ingredientes").val("");
            $("#cboTipo").val("Bodegon");
            $("#imgFoto").attr("src", "./receta_default.jpg");
        };
        Manejadora.btnFiltrar = function () {
            var Manejadora = new RecuperatorioPrimerParcial.Manejadora();
            Manejadora.FiltrarRecetas();
        };
        Manejadora.prototype.FiltrarRecetas = function () {
            var nombre = document.getElementById("nombre").value;
            var tipo = document.getElementById("cboTipo").value;
            var form = new FormData();
            form.append('nombre', nombre);
            form.append('tipo', tipo);
            $.ajax({
                url: './BACKEND/FiltrarReceta.php',
                type: 'POST',
                contentType: false,
                dataType: "json",
                data: form,
                cache: false,
                processData: false,
                async: true
            }).done(function (retJSON) {
                alert("ERROR");
            }).fail(function (jqXHR, textStatus, errorThrown) {
                $("#divTabla").html(jqXHR.responseText);
            });
        };
        Manejadora.btnMostrarRecetasBorradas = function () {
            var Manejadora = new RecuperatorioPrimerParcial.Manejadora();
            Manejadora.MostrarRecetasBorradas();
        };
        Manejadora.prototype.MostrarRecetasBorradas = function () {
            $.ajax({
                url: './BACKEND/MostrarBorrados.php',
                type: 'GET',
                cache: false,
                processData: false,
                async: true
            }).done(function (retJSON) {
                $("#divTabla").html(retJSON);
            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert(+"\n" + textStatus + "\n" + errorThrown);
            });
        };
        Manejadora.CargarTipoJSON = function () {
            $.ajax({
                url: './BACKEND/obtenerJson.php',
                type: 'GET',
                cache: false,
                processData: false,
                async: true
            }).done(function (retJSON) {
                console.log(JSON.parse(retJSON));
                var lista = JSON.parse(retJSON);
                $('#cboTipo').empty();
                for (var index = 0; index < lista.length; index++) {
                    var opcion = lista[index]["descripcion"];
                    $("<option value=\"" + opcion + "\">" + opcion + "</option>").appendTo("#cboTipo");
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert(jqXHR.responseText + "\n" + textStatus + "\n" + errorThrown);
            });
        };
        return Manejadora;
    }());
    RecuperatorioPrimerParcial.Manejadora = Manejadora;
})(RecuperatorioPrimerParcial || (RecuperatorioPrimerParcial = {}));
//# sourceMappingURL=Manejadora.js.map