(function (window) {

    'use strict';

    var Input = function () {
            this.instance = this;
            this.validators = {};
            this.formatters = {};
            this.bin = {};
        },
        validators = {
            'accepted': {
                'fn': function (value) {
                    switch ($.type(value)) {
                        case 'string':
                            return value === '1';
                        case 'number':
                            return value === 1;
                        case 'boolean':
                            return value === true;
                        default:
                            return value == 1;
                    }
                },
                'msg': function (name) {
                    return name+' should be accepted.';
                }
            },
            'after': {
                'fn': function (value, date) {
                    date = new Date(date);
                    return value.getTime() > date.getTime();
                },
                'msg': function (name, date) {
                    return name+' should be after '+date+'.';
                }
            },
            'alpha': {
                'fn': function (value) {
                    return /^[\sA-Z]+$/i.test(value);
                },
                'msg': function (name) {
                    return name+' should be only letters.';
                }
            },
            'alphadash': {
                'fn': function (value) {
                    return /^[-A-Z\s]+$/i.test(value);
                },
                'msg': function (name) {
                    return name+' should be only letters and dashes.';
                }
            },
            'before': {
            'fn': function (value, date) {
                    date = new Date(date);
                    return value.getTime() < date.getTime();
                },
                'msg': function (name, date) {
                    return name+' should be before '+date+'.';
                }
            },
            'between': {
                'fn': function (value, min, max) {
                    switch ($.type(value)) {
                        case 'date':
                            min = new Date(min);
                            max = new Date(max);

                            return value.getTime() > min.getTime() && value.getTime() < max.getTime();
                        case 'string':
                            return value.length > min && value.length < max;
                        default:
                            return value > min && value < max;
                    }
                },
                'msg': function (name, min, max) {
                    return name+' should be between '+min+' and '+max+'.';
                }
            },
            'bool': {
                'fn':  function (value) {
                    return $.type(value) === 'boolean';
                },
                'msg': function (name) {
                    return name+' should be true or false.';
                }
            },
            'date': {
                'fn':  function (value) {
                    return $.type(value) === 'date';
                },
                'msg': function (name) {
                    return name+' should be a date.';
                }
            },
            'decimal': {
                'fn':  function (value) {
                    return $.type(value) === 'number' && Math.round(value) !== value;
                },
                'msg': function (name) {
                    return name+' should be a decimal.';
                }
            },
            'different': {
                'fn':  function (value, other) {
                other = other.val;
                    return value != other;
                },
                'msg': function (name, other) {
                    return name+' should be different from '+other+'.';
                }
            },
            'email': {
                'fn':  function (value) {
                    return /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i.test(value);
                },
                'msg': function (name) {
                    return name+' should be an email.';
                }
            },
            'empty': {
                'fn':  function (value) {
                    return value.length === 0;
                },
                'msg': function (name) {
                    return name+' should be empty.';
                }
            },
            'in': {
                'fn':  function (value, array) {
                    if (array === null) return false;
                    return array.indexOf(value) > -1;
                },
                'msg': function (name, array) {
                    return name+' is invalid.';
                }
            },
            'integer': {
                'fn':  function (value) {
                    return $.type(value) === 'number' && Math.round(value) === value;
                },
                'msg': function (name) {
                    return name+' should be an integer.';
                }
            },
            'ip': {
                'fn':  function (value) {
                    value = value.split('.');
                    if (value.length === 0 || value.length > 4) return false;

                    for (var i = 0; i < value.length; i++) {
                        if (+value[i] < 0 || +value[i] > 255) return false;
                    }

                    return true;
                },
                'msg': function (name) {
                    return name+' should be an ip address.';
                }
            },
            'matches': {
                'fn':  function (value, other) {
                    other = this.instance.get(other).items[0].val;

                    return value === other;
                },
                'msg': function (name, other) {
                    return name+' should be the same as '+other+'.';
                }
            },
            'max': {
                'fn':  function (value, max) {
                    switch ($.type(value)) {
                        case 'string':
                            return value.length > max;
                        default:
                            return value > max;
                    }
                },
                'msg': function (name, max) {
                    return name+' should be longer than '+max+' characters.';
                }
            },
            'min': {
                'fn':  function (value, min) {
                    switch ($.type(value)) {
                        case 'string':
                            return value.length < min;
                        default:
                            return value < min;
                    }  
                },
                'msg': function (name, min) {
                    return name+' should be less than '+min+' characters.';
                }
            },
            'not': {
                'fn':  function (value, array) {
                    if (array === null) return false;
                    if ($.type(array) === 'array') {
                        return array.indexOf(value) === -1;
                    } else {
                        return value !== array;
                    }
                },
                'msg': function (name, array) {
                    return name+' is invalid.';
                }
            },
            'numeric': {
                'fn':  function (value) {
                    switch ($.type(value)) {
                        case 'string':
                            return /^[\d\s]+$/.test(value);
                        case 'number':
                            return true;
                        default:
                            return false;
                    }
                },
                'msg': function (name) {
                    return name+' should be only digits.';
                }
            },
            'required': {
                'fn':  function (value) {
                    return value !== null;
                },
                'msg': function (name) {
                    return name+' is required.';
                }
            },
            'size': {
                'fn':  function (value, size) {
                    return (value+'').length === size;
                },
                'msg': function (name, size) {
                    return name+' should be '+size+' characters long.';
                }
            },
        },
        formatters = {
            'inputName': function (value) {
                value = value.split('_');

                for (var i in value) {
                    value[i] = value[i].charAt(0).toUpperCase()+value[i].slice(1);
                }

                return value.join(' ');
            },
            'default': function (value) {
                return value.trim();
            }
        },
        __construct_prototype = {
            buildError: function (item, failed, args) {
                var message_args;

                args.shift();
                message_args = args.slice(0);
                message_args.unshift(failed, item.name);
                if (this.errors.length === 0)
                    this.errors.push({
                        'thrown': item,
                        'for': failed,
                        'args': args,
                        'message': this.buildMessage.apply(this, message_args)
                    });
            },
            buildMessage: function () {
                var args = Array.prototype.slice.call(arguments, 0),
                    i;
                args = args.slice(1);

                for (i in args) {
                    if ($.type(args[i]) === 'string') args[i] = this.instance.formatters.inputName(args[i]);
                }

                return this.instance.validators[arguments[0]].msg.apply(this, args);
            },
            verbose: function () {
                var passed = ['failed','passed'],
                    msg =  'Input '+passed[+this.passes]+' validition',
                    i;

                if ( ! this.passes) {
                    msg += ': '+this.errors[0].message;
                    for (i = 1; i < this.errors.length; i++) {
                        msg += ' '+this.errors[i].message;
                    }
                } else {
                    msg += '!';
                }

                console.log(msg);

                return this;
            }
        },
        __constructCollection_prototype = {
            getErrorElement: function (name) {
                var name = name.split('_').join('-');
                return '.'+name+'-errors';
            },
            passing: function () {
                var pass = true,
                    i;

                for (i in this) {
                    if (this[i].hasOwnProperty('passes')) {
                        pass = pass && this[i].passes;
                    }
                }

                return pass;
            },
            report: function () {
                var output = '',
                    element,
                    msg,
                    i,
                    error;

                for (i in this) {
                    if (this[i].hasOwnProperty('errors')) {
                        element = this.getErrorElement(i)
                        output = '';

                        for (error in this[i].errors) {
                            msg = this[i].errors[error].message;

                            if (!!msg) output += '<p>'+msg+'</p>';
                        }

                        $('input[name='+i+']').parents('.input').resetToggle(' input-has-errors', !!output);
                        myafterschoolprograms.resetVisibility(element, !!output)

                        if (!!output) {
                            $(element).html(output);
                        }
                    }
                }
            }            
        },
        __i,
        key;

    Input.prototype = {
        has: function (selector) {
            return $('input[name='+selector+']').length > 0;
        },
        get: function (selector) {
            var items = $('input[name='+selector+']');
            if (items.length === 0) items = $('select[name='+selector+']');
            if (items.length === 0) return null;
            return new this.__construct(items, this.instance);
        },
        all: function () {
            var items = $('input');

            if (items.length === 0) return null;

            return new this.__construct(items, this.instance);
        },
        test: function (selector) {
            return new this.__constructCollection(selector); 
        },
        coerce: function (value) {
            // the value is probably a boolean
            if (value === 'true' || value === 'false' || value === '1' || value === '0') return !!value;

            // the value is probably a number
            if (/\d+/.test(value) && value.charAt(0) !== '0' && value.length < 6) return +value;

            var date = new Date(value);
            // the value is probably a date
            if (date != 'Invalid Date') return date;

            if (value === '') return null;

            // the value is a string
            return value; 
        },
        format: function (input) {
            var value = input.value,
                rule = 'default',
                name = input.name;

            if (this.formatters.hasOwnProperty(name)) rule = name;
            if (!!value) value = this.formatters[rule](value);
            value = this.coerce(value);

            return value;
        },
        defineFormat: function (name, callback) {
            this.formatters[name] = callback;
        },
        defineRule: function (name, callback, msg) {

            if (this.bin.hasOwnProperty(name) && ! this.validators.hasOwnProperty(name)) return;

            this.validators[name] = {};
            this.validators[name].fn = callback;
            this.validators[name].msg = msg;
            this.bin[name] = function () {
                var args = Array.prototype.slice.call(arguments, 0),
                    passing,
                    item,
                    i,
                    argstring,
                    str;

                args.unshift(-1);

                for (i = 0; i < this.items.length; i++) {
                    item = this.items[i];
                    args[0] = item.val;
                    passing = this.instance.validators[name].fn.apply(this, args);
                    if ( ! passing) this.buildError(item, name, args);
                    this.passes = (this.passes && passing);
                }

                return this;
            }
        },
        alias: function (alias, name) {
            if (this.bin.hasOwnProperty(alias) || ! this.bin.hasOwnProperty(name)) return;
            this.bin[alias] = this.bin[name];
        },
        __construct: function (items, instance) {
            var item, 
                i, 
                key;

            this.instance   = instance;
            this.items      = [];
            this.errors     = [];
            this.passes     = true;

            for (i = 0; i < items.length; i++) {
                item = items[i];
                item.val = instance.format(item);
                this.items.push(item);
            }

            for (key in instance.validators) {
                if (instance.validators.hasOwnProperty(key)) {
                    this[key] = instance.bin[key];
                }
            }
        },
        __constructCollection: function (inputs) {
            for (var i in inputs) {
                this[i] = inputs[i];
            }
        }
    }

    Input.prototype.__construct.prototype = __construct_prototype;
    Input.prototype.__constructCollection.prototype = __constructCollection_prototype;

    __i = new Input();

    for (key in validators) {
        if (validators.hasOwnProperty(key)) {
            __i.defineRule(key, validators[key].fn, validators[key].msg);
        }
    }

    for (key in formatters) {
        if (formatters.hasOwnProperty(key)) {
            __i.defineFormat(key, formatters[key]);
        }
    }

    window.Input = __i;
})(window);
