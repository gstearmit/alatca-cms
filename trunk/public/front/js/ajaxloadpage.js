$(document).ready(function () {
  
var a ;
   $(window).scroll(function () {

      //a = $(window).height;
      //&&  ( 0 < $(window).scrollTop() ) && $(window).scrollTop()< 635)
      
     alert($(window).scrollTop());

       if ( ($(window).scrollTop() == $(document).height() + $(window).height() ) 
       {
           sendData();
       }
      

       

   });

   function sendData() {
       $.ajax(
        {
            type: "POST",
            url: "http://localhost:12345/index/maybanhang/ajaxpage.php",
            data: "{}",
            //contentType: "application/json; charset=utf-8",
            //dataType: "json",
            async: "false",
            cache: "false",

            success: function (msg) {
                //$("#content-load").append(msg.d);
                $("#content-load").html(msg);
            },

            Error: function (x, e) {
                alert("Some error");
            }
        });
   }
});