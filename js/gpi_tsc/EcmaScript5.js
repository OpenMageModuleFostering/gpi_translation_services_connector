//Ecmascript 5 replacement for old browsers..
if (!Array.isArray) {
	Array.isArray = function (obj) {
		return Object.prototype.toString.call(obj) == "[object Array]";
	};
}

if (!Array.prototype.forEach) {
	Array.prototype.forEach = function (block, thisObject) {
		var len = this.length >>> 0;
		for (var i = 0; i < len; i++) {
			if (i in this) {
				block.call(thisObject, this[i], i, this);
			}
		}
	};
}

if (!Array.prototype.indexOf) {
  Array.prototype.indexOf = function (searchElement) {
    "use strict";
    if (null == this) {
      throw new TypeError();
    }
    var t = Object(this);
    var len = t.length >>> 0;
    if (0 === len) {
      return -1;
    }
    var n = 0;
    if (arguments.length > 0) {
      n = Number(arguments[1]);
      if (n != n) {
        n = 0;
      } else if (n != 0 && n != Infinity && n != -Infinity) {
        n = (n > 0 || -1) * Math.floor(Math.abs(n));
      }
    }
    if (n >= len) {
      return -1;
    }
    var k = n >= 0 ? n : Math.max((len - Math.abs(n), 0));
    for (; k < len; k++) {
      if (k in t && t[k] === searchElement) {
        return k;
      }
    }
    return -1;
  };
}

if (!Object.keys) {
	Object.keys = function (object) {
		var keys = [];
		if (!object) return keys;

		for (var name in object) {
			if (Object.prototype.hasOwnProperty.call(object, name)) {
				keys.push(name);
			}
		}
		return keys;
	};
}

if (!Date.prototype.toISOString) {
	Date.prototype.toISOString = function () {
		return (
				this.getFullYear() + "-" +
					(this.getMonth() + 1) + "-" +
						this.getDate() + "T" +
							this.getHours() + ":" +
								this.getMinutes() + ":" +
									this.getSeconds() + "Z"
			);
	};
}

if (!Date.prototype.toJSON) {
	Date.prototype.toJSON = function () {
		if (typeof this.toISOString != "function")
			throw new TypeError();
		return this.toISOString();
	};
}

if (!String.prototype.trim) {
	var trimBeginRegexp = /^\s\s*/,
			trimEndRegexp = /\s\s*$/;
	String.prototype.trim = function () {
		return String(this).replace(trimBeginRegexp, '').replace(trimEndRegexp, '');
	};
}