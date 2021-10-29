"use strict";
var Entidades;
(function (Entidades) {
    var Persona = /** @class */ (function () {
        function Persona(email, clave) {
            this.clave = clave;
            this.email = email;
        }
        Persona.prototype.ToString = function () {
            var person = JSON.stringify({ email: this.email, clave: this.clave });
            return person.toString();
        };
        return Persona;
    }());
    Entidades.Persona = Persona;
})(Entidades || (Entidades = {}));
//# sourceMappingURL=Persona.js.map