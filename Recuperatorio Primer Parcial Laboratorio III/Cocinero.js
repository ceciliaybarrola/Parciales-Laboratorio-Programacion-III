"use strict";
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
///<reference path="Persona.ts"/>
var Entidades;
(function (Entidades) {
    var Cocinero = /** @class */ (function (_super) {
        __extends(Cocinero, _super);
        function Cocinero(email, clave, especialidad) {
            var _this = _super.call(this, email, clave) || this;
            _this.especialidad = especialidad;
            return _this;
        }
        Cocinero.prototype.ToJSON = function () {
            var cocinero = JSON.parse(_super.prototype.ToString.call(this));
            cocinero["especialidad"] = this.especialidad;
            return cocinero;
        };
        return Cocinero;
    }(Entidades.Persona));
    Entidades.Cocinero = Cocinero;
})(Entidades || (Entidades = {}));
//# sourceMappingURL=Cocinero.js.map