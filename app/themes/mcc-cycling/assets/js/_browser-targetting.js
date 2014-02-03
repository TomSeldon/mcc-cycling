'use strict';

navigator.sayswho= (function(){
    var ua= navigator.userAgent,
        N= navigator.appName, tem,
        M= ua.match(/(opera|chrome|safari|firefox|msie|trident)\/?\s*([\d\.]+)/i) || [];
    M= M[2]? [M[1], M[2]]:[N, navigator.appVersion, '-?'];

    if (M && (tem= ua.match(/version\/([\.\d]+)/i))!= null) {
        M[2]= tem[1];
    }

    return M.join(' ');
})();

function isSafari()
{
    return (navigator.sayswho.indexOf('Safari') >= 0);
}

function isChrome()
{
    return (navigator.sayswho.indexOf('Chrome') >= 0);
}