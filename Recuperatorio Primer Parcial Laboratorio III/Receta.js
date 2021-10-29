"use strict";
var Entidades;
(function (Entidades) {
    var Receta = /** @class */ (function () {
        function Receta(id, nombre, poblacion, pais, foto) {
            this.id = id;
            this.nombre = nombre;
            this.ingredientes = poblacion;
            this.tipo = pais;
            this.foto = foto;
        }
        Receta.prototype.ToJSON = function () {
            var retornoJSON = "{\"id\":\"" + this.id + "\",\"nombre\":\"" + this.nombre + "\",\"ingredientes\":\"" + this.ingredientes + "\",\"tipo\":\"" + this.tipo + "\",\"foto\":\"" + this.foto + "\"}";
            return JSON.parse(retornoJSON);
        };
        return Receta;
    }());
    Entidades.Receta = Receta;
})(Entidades || (Entidades = {}));
//# sourceMappingURL=Receta.js.map