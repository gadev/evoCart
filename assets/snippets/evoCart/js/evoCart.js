Noty.overrideDefaults({
    layout   : 'topRight',
    theme    : 'metroui',
    timeout  : 1500,
});

var ec_delivery = document.querySelector('.js-ec-delivery'),
    ec_city = document.querySelector('.js-ec-city'),
    ec_cart = document.querySelector('.js-ec-cart');

var itemName = [' товар', ' товара', ' товаров'];

var evoCart;
var ecHelpers;

ecHelpers = {
    ajax: function(type, url, data, success, error) {
        var request = new XMLHttpRequest();
        var urlEncodedData = "";
        var urlEncodedDataPairs = [];
        var name;
        request.open(type, url, true);
        request.setRequestHeader('X-REQUESTED-WITH', 'XMLHttpRequest');
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.onload = function() {
            if (parseInt(request.status) >= 200 && parseInt(request.status) < 400) {
                if(typeof success === 'function') {
                    success(request.responseText);
                }
            } else {
                if(typeof error === 'function') {
                    error(request.responseText);
                } else {
                    new Noty({
                        type: 'error',
                        text: request.responseText ? request.responseText : 'Ошибка',
                    }).show();
                }
            }
        };
        request.onerror = function() {
            alert('error');
        };
        for(name in data) {
            urlEncodedDataPairs.push(encodeURIComponent(name) + '=' + encodeURIComponent(data[name]));
        }
        urlEncodedData = urlEncodedDataPairs.join('&').replace(/%20/g, '+');
        request.send(urlEncodedData);
    },
    format: function (number, titles) {
        cases = [2, 0, 1, 1, 1, 2];
        return titles[(number % 100 > 4 && number % 100 < 20) ? 2 : cases[(number % 10 < 5) ? number % 10 : 5]];
    },
    getParents: function(el, parentSelector) {
        if (parentSelector === undefined) {
            parentSelector = document;
        }
        var parents = [];
        var p = el.parentNode;
        while (p !== parentSelector) {
            var o = p;
            parents.push(o);
            p = o.parentNode;
        }
        parents.push(parentSelector);
        return parents;
    },
    counter: function(el, int) {
        var row = document.getElementById(el.dataset.key),
            countEl = row.querySelector('.js-ec-count'),
            countInp = row.querySelector('.js-ec-count-input'),
            count = 0;
        if(countEl) {
            count = parseInt(countEl.textContent) + int;
            countEl.textContent = count;
        } else if (countInp) {
            count = parseInt(countInp.value) + int;
            countInp.value = count;
        }
        return count;
    },
    loading: function(action) {
        if(ec_cart) {
            if(action) {
                ec_cart.classList.add('js-ec-loading');
            } else {
                ec_cart.classList.remove('js-ec-loading');
            }
        }
    }
};
evoCart = {
    add: function (e) {
        var _data = [];
        for(var i in e.dataset ) {
            var key = i;
            _data[key] = e.dataset[i];
        }
        /*
        //TODO: надо добавить возможность выбора кол-ва товаров для добавления
        if (e.dataset.qtyField !== undefined) {
            _qnt = $('input[name='+e.dataset.qtyField+']').val();
            if (_qnt < 1) {
                _qnt = 1;
                $('input[name='+e.dataset.qtyField+']').val(_qnt);
            }
            _data.count = _qnt;
        }
        */
        var _obj = Object.assign({}, _data);
        ecHelpers.ajax('POST', '/cart_add', _obj, function(data) {
            evoCart.updateCartStatus(data);
            new Noty({
                type: 'success',
                text: 'Товар добавлен в корзину',
            }).show();
        });
    },
    remove: function (e) {
        var _key = e.dataset.key;
        if(_key.length === 0) {
            new Noty({
                type: 'error',
                text: 'Ошибка передачи ключа',
            }).show();
            return false;
        }
        ecHelpers.ajax('POST', '/cart_remove', { key: _key }, function(data) {
            evoCart.updateCartStatus(data);
            var _el = document.getElementById(_key);
            if(_el) {
                _el.parentNode.removeChild(_el);
            }
            new Noty({
                type: 'info',
                text: 'Товар удален из корзины',
            }).show();
            evoCart.getFullCart();
        });
    },
    clear: function () {
        ecHelpers.ajax('get', '/cart_clear', '', function(data) {
            evoCart.updateCartStatus(data);
            new Noty({
                type: 'info',
                text: 'Корзина очищена',
            }).show();
            if(window.isCart) {
                evoCart.getFullCart();
            }
        });
    },
    update: function (e, count) {
        var _key = e.dataset.key;
        if (count <= 0) {
            this.remove(e);
        } else {
            ecHelpers.ajax('post', '/cart_update', {
                    key: _key,
                    count: count
                }, function(data) {
                    evoCart.updateCartStatus(data);
                    evoCart.getFullCart();
                }, function(data) {
                    new Noty({
                        type: 'error',
                        text: data ? data : 'Ошибка',
                    }).show();
                    evoCart.getFullCart();
                }
            );
        }
    },
    getStatus: function () {
        ecHelpers.ajax('GET', '/cart_status', null, function(data) {
            evoCart.updateCartStatus(data);
        });
    },
    getFullCart: function () {
        ecHelpers.loading(true);
        var _data = {
            delivery: ec_delivery ? ec_delivery.value : null,
            city: ec_city ? ec_city.value : null
        };
        ecHelpers.ajax('POST', '/cart_full', _data, function(json) {
            data = JSON.parse(json);
            document.querySelector('.js-ec-cart').innerHTML = data.cart;
            document.querySelectorAll('.js-ec-total').forEach(function(item) {
                item.innerHTML = data.total;
            });
            document.querySelectorAll('.js-ec-shipping').forEach(function(item) {
                item.innerHTML = data.shipping;
            });
            document.querySelectorAll('.js-ec-dsq').forEach(function(item) {
                item.innerHTML = data.dsq;
            });
            document.querySelectorAll('.js-ec-itogo').forEach(function(item) {
                item.innerHTML = data.itogo;
            });
            ecHelpers.loading(false);
        }, function(data) {
            var el = document.querySelector('.js-ec-ifempty');
            if(el) {
                el.parentNode.removeChild(el);
            }
            document.querySelector('.js-ec-container').innerHTML = data;
            document.querySelectorAll('.js-ec-total').forEach(function(item) {
                item.innerHTML = '';
            });
        });
    },
    updateCartStatus: function (data) {
        var _res = JSON.parse(data);
        if (_res.data) {
            document.querySelectorAll('.js-ec-qty').forEach(function(item){
                item.textContent = _res.data.qty;
            })
            document.querySelectorAll('.js-ec-qty-txt').forEach(function(item){
                item.textContent = ecHelpers.format(_res.data.qty, itemName);
            })
        }
    }
};

//check cart status on page load
window.onload = evoCart.getStatus();
//bindings
document.addEventListener('click', function (event) {
    var _target = event.target;
    if(_target.classList.contains('js-ec-add')) {
        evoCart.add(_target);
    } else if(_target.parentNode.classList.contains('js-ec-add')) {
        evoCart.add(_target.parentNode);
    }
});
document.querySelectorAll('.js-ec-clear').forEach(function(item) {
    item.addEventListener("click", function() {
        evoCart.clear();
    })
});
//cart bindings
if(ec_cart) {
    ec_cart.addEventListener('click', function (event) {
        var _target = event.target;
        if (_target.classList.contains('js-ec-remove')) {
            evoCart.remove(event.target);
        } else if(_target.classList.contains('js-ec-increment')) {
            _count = ecHelpers.counter(_target, 1);
            evoCart.update(_target, _count);
        } else if(_target.classList.contains('js-ec-decrement')) {
            _count = ecHelpers.counter(_target, -1);
            evoCart.update(_target, _count);
        }
    });
}
//shipping change
if(ec_delivery) {
    ec_delivery.addEventListener('change', function(event) {
        evoCart.getFullCart();
    });
}
if(ec_city) {
    ec_city.addEventListener('change', function(event) {
        evoCart.getFullCart();
    });
}
