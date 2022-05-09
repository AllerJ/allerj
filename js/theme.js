jQuery(function($) {
    const pageEverything = $('html');
    const contrastBtn = $('.acctoggle__contrast');
    const fontButton = $('.acctoggle__fontsize');

    function checkContrastCookie() {
        if (getCookie('contrast-cookie') != 'false') {
            contrastBtn.toggleClass('active');
        }
    }

    function checkFontSizeCookie() {
        if (getCookie('font-size-cookie') != 'false') {
            fontButton.toggleClass('active');
        }
    }

    function setCookie(cname, cvalue, exdays = 365) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return 'false';
    }


    $(document).ready(function() {
        checkContrastCookie();
        checkFontSizeCookie();
    });

    contrastBtn.on('click', function() {
        $(this).toggleClass('active');
        pageEverything.toggleClass('accessibility__contrast');
        pageEverything.toggleClass('no-con');

        if (getCookie('contrast-cookie') == 'false') {
            setCookie('contrast-cookie', $(this).hasClass('active'));
        } else {
            setCookie('contrast-cookie', 'false');

        }
    });

    fontButton.on('click', function() {
        $(this).toggleClass('active');
        pageEverything.toggleClass('accessibility__fontsize');
        pageEverything.toggleClass('no-font');

        if (getCookie('font-size-cookie') == 'false') {
            setCookie('font-size-cookie', $(this).hasClass('active'));
        } else {
            setCookie('font-size-cookie', 'false');
        }
    });
});