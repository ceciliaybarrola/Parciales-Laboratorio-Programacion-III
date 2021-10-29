"use strict";
var PrimerParcial;
(function (PrimerParcial) {
    // , Iparte3
    var Manejadora = /** @class */ (function () {
        function Manejadora() {
        }
        Manejadora.AgregarProductoJSON = function () {
            var nombre = document.getElementById("nombre").value;
            var origen = document.getElementById("cboOrigen").value;
            var form = new FormData();
            form.append("nombre", nombre);
            form.append("origen", origen);
            var ajax = new Ajax();
            ajax.Post("./Backend/AltaProductoJSON.php", function (param) {
                alert(param);
                console.log(param);
            }, form);
        };
        Manejadora.MostrarProductosJSON = function () {
            var ajax = new Ajax();
            ajax.Get("./Backend/ListadoProductosJSON.php", function (param) {
                if (param == "No se recibio GET") {
                    alert(param);
                    console.log(param);
                }
                else {
                    var tabla = "<table width=100% border = '2'><tr><td>Nombre</td><td>origen</td></tr>";
                    var lista = JSON.parse(param);
                    for (var i = 0; i < lista.length; i++) {
                        tabla += '<tr><td>';
                        tabla += lista[i].nombre;
                        tabla += '</td><td>';
                        tabla += lista[i].origen;
                        tabla += '</td></tr>';
                    }
                    document.getElementById("divTabla").innerHTML = tabla;
                }
            });
        };
        Manejadora.VerificarProductoJSON = function () {
            var nombre = document.getElementById("nombre").value;
            var origen = document.getElementById("cboOrigen").value;
            var form = new FormData();
            form.append("nombre", nombre);
            form.append("origen", origen);
            var ajax = new Ajax();
            ajax.Post("./Backend/VerificarProductoJSON.php", function (param) {
                param = JSON.parse(param);
                alert(param.mensaje);
                console.log(param.mensaje);
            }, form);
        };
        Manejadora.MostrarInfoCookie = function () {
            var nombre = document.getElementById("nombre").value;
            var origen = document.getElementById("cboOrigen").value;
            var ajax = new Ajax();
            ajax.Get("./Backend/MostrarCookie.php", function (param) {
                param = JSON.parse(param);
                alert(param.mensaje);
                console.log(param.mensaje);
            }, "nombre=" + nombre + "&origen=" + origen);
        };
        Manejadora.AgregarProductoSinFoto = function () {
            var nombre = document.getElementById("nombre").value;
            var origen = document.getElementById("cboOrigen").value;
            var codigoBarra = document.getElementById("codigoBarra").value;
            var precio = document.getElementById("precio").value;
            var productoObj = new Entidades.ProductoEnvasado(nombre, origen, 0, parseInt(codigoBarra), parseInt(precio), ""); //lo hago tipo json
            console.log(productoObj);
            var producto = productoObj.ToJSON();
            console.log(producto);
            var form = new FormData();
            form.append("producto_json", producto);
            console.log(form);
            var ajax = new Ajax();
            ajax.Post("./backend/AgregarProductoSinFoto.php", function (param) {
                //(<HTMLInputElement> document.getElementById("id")).value = "";
                document.getElementById("nombre").value = "";
                document.getElementById("precio").value = "";
                document.getElementById("codigoBarra").value = "";
                document.getElementById("cboOrigen").value = "1";
                //alert(JSON.parse(param).mensaje);
                console.log(param);
            }, form);
        };
        Manejadora.MostrarProductosEnvasados = function () {
            var ajax = new Ajax();
            ajax.Get("./BACKEND/ListadoProductosEnvasados.php", function (param) {
                var producto_json = JSON.parse(param);
                var tabla = "<table border=2>";
                tabla += "<thead>";
                tabla += "<tr>";
                tabla += "<td>Id</td>";
                tabla += "<td>Nombre</td>";
                tabla += "<td>origen</td>";
                tabla += "<td>codigoBarra</td>";
                tabla += "<td>Precio</td>";
                tabla += "<td>Foto</td>";
                tabla += "<td>Acciones</td>";
                tabla += "</tr>";
                tabla += "</thead>";
                tabla += "<tbody>";
                for (var i = 0; i < producto_json.length; i++) {
                    var producto = new Entidades.ProductoEnvasado(producto_json[i].nombre, producto_json[i].origen, producto_json[i].id, producto_json[i].codigoBarra, producto_json[i].precio, producto_json[i].pathFoto);
                    tabla += '<tr><td>';
                    tabla += producto.id;
                    tabla += '</td><td>';
                    tabla += producto.nombre;
                    tabla += '</td><td>';
                    tabla += producto.origen;
                    tabla += '</td><td>';
                    tabla += producto.codigoBarra;
                    tabla += '</td><td>';
                    tabla += producto.precio;
                    tabla += '</td><td>';
                    tabla += "<img src='" + producto.pathFoto + "' width=50 height=50 />";
                    tabla += '</td>';
                    tabla += "<td><input type='button' value='Borrar' onclick='PrimerParcial.Manejadora.EliminarProducto(" + producto.ToJSON() + ")' />";
                    tabla += "<input type='button' value='Modificar' onclick='PrimerParcial.Manejadora.ModificarProducto(" + producto.ToJSON() + ")' /></td>";
                    tabla += '</td></tr>';
                }
                tabla += "</tbody>";
                tabla += "</table>";
                document.getElementById("divTabla").innerHTML = tabla;
            }, "tabla=json");
        };
        Manejadora.prototype.ModificarProducto = function (producto_json) {
            document.getElementById("idProducto").value = producto_json.id;
            document.getElementById("nombre").value = producto_json.nombre;
            document.getElementById("precio").value = producto_json.precio;
            document.getElementById("codigoBarra").value = producto_json.codigoBarra;
            document.getElementById("cboOrigen").value = producto_json.origen;
        };
        Manejadora.prototype.EliminarProducto = function (producto_json) {
            if (!confirm("Desea eliminar al prodcuto seleccionado? nombre: " + producto_json.nombre + " origen: " + producto_json.origen))
                return;
            var producto = JSON.stringify(producto_json);
            var ajax = new Ajax();
            var form = new FormData();
            form.append("producto_json", producto);
            ajax.Post("./Backend/EliminarProductoENvasado.php", function (param) {
                alert(JSON.parse(param).mensaje);
                console.log(JSON.parse(param).mensaje);
                if (JSON.parse(param).exito) {
                    Manejadora.MostrarProductosEnvasados();
                }
            }, form);
        };
        Manejadora.ModificarSinFoto = function () {
            var id = document.getElementById("idProducto").value;
            var nombre = document.getElementById("nombre").value;
            var origen = document.getElementById("cboOrigen").value;
            var codigoBarra = document.getElementById("codigoBarra").value;
            var precio = document.getElementById("precio").value;
            var form = new FormData();
            var producto = new Entidades.ProductoEnvasado(nombre, origen, parseInt(id), parseInt(codigoBarra), parseInt(precio));
            form.append("producto_json", producto.ToJSON());
            var ajax = new Ajax();
            ajax.Post("./Backend/ModificarProductoEnvasado.php", function (param) {
                var aux = JSON.parse(param);
                if (aux.exito == true) {
                    alert(aux.mensaje);
                    console.log(aux.mensaje);
                    Manejadora.MostrarProductosEnvasados();
                    document.getElementById("idProducto").value = "";
                    document.getElementById("nombre").value = "";
                    document.getElementById("cboOrigen").value = "Argentina";
                    document.getElementById("codigoBarra").value = "";
                    document.getElementById("precio").value = "";
                }
                else {
                    alert(aux.mensaje);
                    console.log(aux.mensaje);
                }
            }, form);
        };
        return Manejadora;
    }());
    PrimerParcial.Manejadora = Manejadora;
})(PrimerParcial || (PrimerParcial = {}));
//# sourceMappingURL=manejadora.js.map