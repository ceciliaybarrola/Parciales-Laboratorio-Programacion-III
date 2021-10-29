var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (Object.prototype.hasOwnProperty.call(b, p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };
    return function (d, b) {
        if (typeof b !== "function" && b !== null)
            throw new TypeError("Class extends value " + String(b) + " is not a constructor or null");
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
var Ajax = /** @class */ (function () {
    function Ajax() {
        var _this = this;
        this.Get = function (ruta, success, params, error) {
            if (params === void 0) { params = ""; }
            var parametros = params.length > 0 ? params : "";
            ruta = params.length > 0 ? ruta + "?" + parametros : ruta;
            _this._xhr.open('GET', ruta);
            _this._xhr.send();
            _this._xhr.onreadystatechange = function () {
                if (_this._xhr.readyState === Ajax.DONE) {
                    if (_this._xhr.status === Ajax.OK) {
                        success(_this._xhr.responseText);
                    }
                    else {
                        if (error !== undefined) {
                            error(_this._xhr.status);
                        }
                    }
                }
            };
        };
        this.Post = function (ruta, success, params, error) {
            if (params === void 0) { params = ""; }
            //let parametros:string = params.length > 0 ? params : "";
            _this._xhr.open('POST', ruta, true);
            //this._xhr.setRequestHeader("content-type","application/x-www-form-urlencoded");
            _this._xhr.send(params);
            _this._xhr.onreadystatechange = function () {
                if (_this._xhr.readyState === Ajax.DONE) {
                    if (_this._xhr.status === Ajax.OK) {
                        success(_this._xhr.responseText);
                    }
                    else {
                        if (error !== undefined) {
                            error(_this._xhr.status);
                        }
                    }
                }
            };
        };
        this._xhr = new XMLHttpRequest();
        Ajax.DONE = 4;
        Ajax.OK = 200;
    }
    return Ajax;
}());
var Entidades;
(function (Entidades) {
    var Producto = /** @class */ (function () {
        function Producto(nombre, origen) {
            this.nombre = nombre;
            this.origen = origen;
        }
        Producto.prototype.ToString = function () {
            return "\"nombre\" : \"" + this.nombre + "\", \"origen\" : \"" + this.origen + "\"";
        };
        Producto.prototype.ToJSON = function () {
            return "{" + this.ToString() + "}";
        };
        return Producto;
    }());
    Entidades.Producto = Producto;
})(Entidades || (Entidades = {}));
var Entidades;
(function (Entidades) {
    var ProductoEnvasado = /** @class */ (function (_super) {
        __extends(ProductoEnvasado, _super);
        function ProductoEnvasado(nombre, origen, id, codigoBarra, precio, pathFoto) {
            if (nombre === void 0) { nombre = " "; }
            if (origen === void 0) { origen = " "; }
            if (id === void 0) { id = 0; }
            if (codigoBarra === void 0) { codigoBarra = null; }
            if (precio === void 0) { precio = 0; }
            if (pathFoto === void 0) { pathFoto = ""; }
            var _this = _super.call(this, nombre, origen) || this;
            _this.id = id;
            _this.codigoBarra = codigoBarra;
            _this.precio = precio;
            _this.pathFoto = pathFoto;
            return _this;
        }
        ProductoEnvasado.prototype.ToJSON = function () {
            return "{" + _super.prototype.ToString.call(this) + ", \"id\" : " + this.id + ", \"codigoBarra\" : " + this.codigoBarra + ", \"precio\" : " + this.precio + " , \"pathFoto\" : \"" + this.pathFoto + "\"}";
        };
        return ProductoEnvasado;
    }(Entidades.Producto));
    Entidades.ProductoEnvasado = ProductoEnvasado;
})(Entidades || (Entidades = {}));
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
            var producto = productoObj.ToJSON();
            var form = new FormData();
            form.append("producto_json", producto);
            var ajax = new Ajax();
            ajax.Post("./backend/AgregarProductoSinFoto.php", function (param) {
                //(<HTMLInputElement> document.getElementById("id")).value = "";
                document.getElementById("nombre").value = "";
                document.getElementById("precio").value = "";
                document.getElementById("codigoBarra").value = "";
                document.getElementById("cboOrigen").value = "1";
                alert(JSON.parse(param).mensaje);
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
