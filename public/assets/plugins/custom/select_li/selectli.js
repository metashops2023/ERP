var ul = document.getElementById('list');
var liSelected;
var index = -1;
document.addEventListener('keydown', function (event) {
    var len = ul.getElementsByTagName('a').length - 1;
    if (event.which === 40) {
        document.getElementById('search_product').value = '';
        index++;
        //down 
        if (liSelected) {
            removeClass(liSelected, 'selectProduct');
            next = ul.getElementsByTagName('a')[index];
            if (typeof next !== undefined && index <= len) {
                liSelected = next;
            } else {
                index = 0;
                liSelected = ul.getElementsByTagName('a')[0];
            }
            addClass(liSelected, 'selectProduct');
        } else {
            index = 0;
            liSelected = ul.getElementsByTagName('a')[0];
            addClass(liSelected, 'selectProduct');
        }
    } else if (event.which === 38) {
        //up
        if (liSelected) {
            removeClass(liSelected, 'selectProduct');
            index--;
            next = ul.getElementsByTagName('a')[index];
            if (typeof next !== undefined && index >= 0) {
                liSelected = next;
            } else {
                index = len;
                liSelected = ul.getElementsByTagName('a')[len];
            }
            addClass(liSelected, 'selectProduct');
        } else {
            index = 0;
            liSelected = ul.getElementsByTagName('a')[len];
            addClass(liSelected, 'selectProduct');
        }
    }
}, false);

function removeClass(el, className) {
    if (el.classList) {
        el.classList.remove(className);
    } else {
        el.className = el.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
    }
};

function addClass(el, className) {
    if (el.classList) {
        el.classList.add(className);
    } else {
        el.className += ' ' + className;
    }
};