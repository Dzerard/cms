//contact plugin

(function($){

   $.fn.contactPlugin = function contactPlugin()
   {
        $('#contact-form input[type="text"]').on('blur', function(e) {                
            $(this).parent().find('i').removeClass('active');
            return false;
        });
        $('#contact-form input[type="text"]').on('focus', function(e) {                
            $(this).parent().find('i').addClass('active');
            return false;
        });      
        $('#contact-form .form-control').on('click', function(e) {                
            $(this).focus();
            return false;
        });
        
        function oLoader() {  

        return '<div class="default-loader">\n\
                   <img src="css/spinners/load.gif" alt="loading ...">\n\
                </div>';    
        }   
        
        function contactModalCompany() {       
         
         $('.sendContactFormAjax').on('click','.btn-primary', function(e) {
            
             var form  = $('.sendContactFormAjax');
             submitContactCompanyForm(form);
             e.preventDefault;
             return false;      
         });
         }
        
        function submitContactCompanyForm(oForm) {
        
            var oUrl = 'ajax/message';                
            var form = oForm;
        
            if (form.validationEngine('validate')) {
                
                var data = form.serialize();
                console.log(data);
                
                form.fadeOut(function() {                     
                     form.html(oLoader()).fadeIn('fast');
                });            

                setTimeout(function() {              
                    $.ajax({

                         type : 'POST',                       
                         url  : oUrl,
                         data : data,

                         success : function(result) {
                            
                            form.fadeOut(function() {
                                form.html('<div class="alert alert-info">'+translator('mail_send')+'</div>').fadeIn();                                         
                            });                               

                         },
                         error : function(xmlHttpRequest, textStatus, errorThrown) {                                   
                           
                            form.fadeOut(function() {
                                form.html('<div class="alert alert-danger">'+translator('mail_err')+'</div>').fadeIn();                                          
                            });                                

                        }
                    });
                    },1500);
                
            }
            return false;    
        
        }    
//        
        function validateContactCompanyForm() {
            if ($('.sendContactFormAjax.validate').length > 0) {
                $('.sendContactFormAjax.validate').validationEngine(
                    'attach', {

                        autoHidePrompt : 'false',
                        autoHideDelay : '7000',
                        fixed : true,
                        scroll : true,
                        binded : false,                     
                        promptPosition : 'bottomLeft'

                    }
                );
            }        
        }
        
       
        //smooth scroll
        $('body').on('click', '.companyAboutNav,.companyFilesNav, .companyWorkNav, .companyContactNav', function() {

         var scrollTo = $(this).attr('href');           
         $('body #company-modal').animate({
            scrollTop: $(scrollTo).offset().top - 30},
            'medium');                
            return false;
        });        

        function renderGoogleMaps() {

        var elements = $('.modal-dialog.company-dialog .google-map');

        elements.each(function() {

            var latlng = $(this).attr('data-latlng').split(',');
            var lat = jQuery.trim(latlng[0]);
            var lng = jQuery.trim(latlng[1]);
            var address = $(this).attr('data-address');
            var displayType = $(this).attr('data-display-type');
            var zoomLevel = parseInt($(this).attr('data-zoom-level'));
            $(this).css('height', $(this).attr('data-height'));

            switch(displayType.toUpperCase()) {
                case 'ROADMAP' : displayType = google.maps.MapTypeId.ROADMAP; break;
                case 'SATELLITE' : displayType = google.maps.MapTypeId.SATELLITE; break;
                case 'HYBRID' : displayType = google.maps.MapTypeId.HYBRID; break;
                case 'TERRAIN' : displayType = google.maps.MapTypeId.TERRAIN; break;
                default : displayType = google.maps.MapTypeId.ROADMAP; break;
            }

            var geocoder = new google.maps.Geocoder();
            var latlng = new google.maps.LatLng(lat, lng);
            var myOptions = {
                scrollwheel : false,
                zoom : zoomLevel,
                center : latlng,
                mapTypeId : displayType,
                marker : 'css'
            }

            var map = new google.maps.Map($(this)[0], myOptions);

            geocoder.geocode({
                'address' : address,
                'latLng' : latlng,
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {

                    if(jQuery.trim(address).length > 0) {
                        var marker = new google.maps.Marker({
                            map : map,
                            position : results[0].geometry.location,                           
                            draggable:false,
                            icon: 'js/models/img/marker.png',
                            animation: google.maps.Animation.DROP

                        });

                        map.setCenter(results[0].geometry.location);

                    } else {
                        var marker = new google.maps.Marker({
                            map : map,
                            position : latlng
                        });

                        marker.setPosition(latlng); map.setCenter(latlng);

                    }

                } else {
                    alert("Geocode was not successful for the following reason: " + status);
                }
            });

        });
    }
    
    contactModalCompany();
    validateContactCompanyForm();
    $('[rel="tooltip"]').tooltip();
    renderGoogleMaps();
   };
})(jQuery);


       