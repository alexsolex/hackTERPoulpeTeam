
$(document).ready(function () {

      _Comulien.Controller.App.init();
      _Comulien.Controller.Public.init();
      $(document).ajaxSend(function() {
        $.mobile.loading( 'show');
        });
        $(document).ajaxComplete(function() {
            $.mobile.loading( 'hide');
        });
 
});
