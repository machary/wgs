var options, a;
jQuery(function(){
    options = { serviceUrl: 'http://localhost/seskoal/public/peta/index/autocomplete' };
    $('#find-location').autocomplete({source:options});
});