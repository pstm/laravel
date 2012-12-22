// app.js

var Muli = (function( $, window, document, undefined ) {
    'use strict';

    $('[data-cookie]').on('click', function(){

    	$.cookie('lang', $(this).data('lang'), { expires: 365, path: '/' });

    });

} ( jQuery, window, document ) );