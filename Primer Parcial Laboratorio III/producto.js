"use strict";
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
//# sourceMappingURL=producto.js.map