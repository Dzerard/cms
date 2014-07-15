
    
     var locale   = $('html').attr('lang');    
     var messages = {'pl': {
                        'switching' : 'Zmieniam język',
                        'ok'        : 'Język został zmieniony',
                        'err'       : 'Wystapił błąd',
                        'reload'    : 'Strona zostanie przeładowana ... ',
                        'pl'        : 'PL',
                        'mail_send' : 'Wiadomość została wysłana',
                        'mail_err'  : 'Wystapił błąd',
                        'loading'   : 'Ładowanie ...'
                    },
                    'en': {
                        'switching' : 'Switching language',
                        'ok'        : 'Language has been set',
                        'reload'    : 'Reloading page ...',
                        'en'        : 'UK',
                        'mail_send' : 'Message has been send',
                        'mail_err'  : 'Error occured',
                        'loading'   : 'Loading ...',
                        'Ukryj'     : 'Hide'
                    }
                };
    //tranalstor
    function translator(text) {
        
        var lang = locale;
        var oText = '';
        
        if (lang === 'pl') {            
    
            $.each(messages.pl, function( index, value ) {                  
                if(index === text) { oText = value; } 
            });
            
        } else {
            $.each(messages.en, function( index, value ) {                  
                if(index === text) { oText = value; } 
            });
        }
        
        if(oText === '') { oText = text; }
        
        return oText;
        
    };    
    
    
    
jQuery(document).ready(function($) {    
   
    function loadInformationCompany() {
        
        $('.show-company').on('click', function() {
          
            $.ajax({
                type : 'GET',                       
                url  : 'ajax/company',
                //data : data,

                success : function(result) {
                    
                    //$(result).modal('show');
                    $('body').append(result);
                    var oModal = $(document).find('#company-modal');
                    oModal.modal('show');
                    
                    //after close destroy object
                    oModal.on('hidden.bs.modal', function (e) {
                        oModal.remove();
                    });
                                      
                }
            });
            return false;
        });
        
    }
    
   // loadInformationCompany();
    //configuration
    topMenu();
    $('[rel=tooltip]').tooltip(); //tootlip
    contactModal();
    validateForm();
    settingLanguage();
       
       
    function topMenu() {
        $('body').on('click', '.expand-button', function() {
            var navbar = $('.navbar.navbar-default');
            var navbarSmall = $('.show-navbar-menu');
            var footer = $('#footer');
            
            if(navbar.is(':visible')) {
                
//                footer.animate({height: "0px", padding: "0px",  minHeight:"0px"},500);
//                navbar.animate({height: "0px", minHeight:"1px"}, 500, function(){
//                    navbarSmall.hide();
//                    navbarSmall.removeClass('hidden');
//                    navbarSmall.fadeIn();
//                    navbar.hide();
//                });
                footer.fadeOut(); 
                navbar.fadeOut(function() {                                   
                    navbarSmall.hide();
                    navbarSmall.removeClass('hidden');
                    navbarSmall.fadeIn();

                });
                
            } else {
                
                 navbarSmall.fadeOut(function(){
//                     navbar.show();
//                     navbar.animate({height: "50px", minHeight:"50px"}, 500);
//                     footer.animate({height: "35px",  padding: "4px"}, 500);
                     navbar.fadeIn();
                     footer.fadeIn();
                 });
                    
            } 
           return false;
        });
        
    }    
    
    function contactModal() {       
         
         $('#contactModal').on( "click", ".form-group .form-control", function(e) { $(e.target).focus();});

         $('#contactModal .modal-footer').on('click','.btn-primary', function(e) {
             
             var modal = $('#contactModal');
             var form  = $('#contactModal').find('form');
             submitContactForm(form, modal);
             e.preventDefault;
             return false;      
         });
    }
    
    function submitContactForm(oForm, oModal) {
        
            var oUrl = 'ajax/addMessage';                
            var form = oForm;
        
            if (form.validationEngine('validate')) {
                
                var data = form.serialize();
                form.fadeOut(function() {
                     oModal.find('.modal-footer').hide();  // tutaj wiecej poprawek by sie zdało :)
                     form.html(defineLoader()).fadeIn('fast');
                });            
                console.log(data);
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
    
    function validateForm() {
        if ($('form.validate').length > 0) {
            $('form.validate').validationEngine(
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
    //helper functions do klasy i modelu
    function defineLoader() {  

        return '<div class="default-loader">\n\
                   <img src="css/spinners/load.gif" alt="loading ...">\n\
                </div>';    
    }
    
    function niceLang(locale) {
         var nLang = '';
         if(locale === 'pl') {
             nLang = 'PL';
         } else {
             nLang = 'UK';
         }
         return nLang;
    }
    
    function settingLanguage() {
        
    
        $('.set-language').on('click', function() {

            var lang = $(this).attr('rel');
            var data = {'langId': lang};

            if(niceLang(locale) === lang ) {
                return false;
            }

            $('body').append('<div class="ajaxShadow"><p>'+translator('switching')+'</p></div>');     
            var info = $(document).find('.ajaxShadow p');  
            
            
            $.ajax({

                    type : 'POST',                       
                    url  : '',
                    data : data,

                    success : function(result) {
                        //@todo lepsza obsluga
                        if(result) {
                            info.hide();
                            info.text(translator('ok')).fadeIn( function(){
                                info.fadeOut('fast',function(){
                                    info.text(translator('reload')).fadeIn();
                                });                                
                                setTimeout(function(){                                
                                    document.location.href='';
                                },1000);                     
                            });
                        }
                    },
                    error : function(xmlHttpRequest, textStatus, errorThrown) {                                   

                        info.hide();
                        info.text(translator('err')).fadeIn('fast');
                   }
            }); 

           return false;
        });    
    }
    function showMap() {
                
        $('.map-icon').on('click', '.btn-default', function(){
            var map    = $('#map');
            var button = $(this);
            
            map.hide();
            map.removeClass('hidden');          
            button.tooltip('hide');
            
            button.parent().animate({left: "-45px"},300, function(){
                map.fadeIn();
            });
            
            return false;
        });
        
        $('#map').on('click', '.close', function(){
            var map       = $('#map');
            var button    = $('.map-icon .btn-default');
            
            map.fadeOut(function(){                
                button.parent().animate({left: "0px"},300);                    
            });
            return false;
        });
        
    }
    showMap();
    
    function showStats() {
        
        $('.stats-icon').on('click', '.btn-default', function(){
            var stats   = $('#stats');
            var button = $(this);
            //var close = stats.find('#close-stats');
            if(!stats.parent().find('.cloosee').length > 0) {
                
            
                var closeBtn = '<a class="close cloosee" id="close-stats" >×</a>';

                stats.after($(closeBtn)
                        .hide()
                        .attr('title', translator('Ukryj'))
                        .attr('data-placement', 'right')
                        //.bind('click', function(){ closeStats(stats);})
                        .tooltip());
                
                closeStats();
            } else {
               // stats.parent().find('.cloosee').
            }
            
            button.tooltip('hide');
            button.parent().animate({left: "-45px"},300, function(){
               stats.fadeIn();
               stats.next().fadeIn();
            });
            
            return false;
        });
        
        function closeStats() {
            
            var item = $('#stats').parent().find('#close-stats');     
            $(item).on('click', function(e){
                var closeBtn  = $(this);
                var stats     = $('#stats');
                var button    = $('.stats-icon .btn-default');
                closeBtn.tooltip('hide');
                closeBtn.hide();
                stats.fadeOut(function(){                
                    button.parent().animate({left: "0px"},300);                    
                });
                return false;
            });
        }
        
    }
    showStats();
    
    
   //evolution
//   $('div.navbar.navbar-default.navbar-fixed-top').mouseover(function(event){
//        //simulateClick();
//        return;        
//    });
    
//    function simulateClick() {
   //     var event = KeyboardEvent('click'    );
   //     var event = new KeyboardEvent("keydown", {bubbles : true, cancelable : true, key : "Q", char : "Q", shiftKey : true});
//        var event = new KeyboardEvent("keydown", {
//        bubbles : true,
//        cancelable : true,
//        char : "Q",
//        key : "q",
//        shiftKey : true,
//        keyCode : 81
//    });
//      element.dispatchEvent(e);
//      var keyEvent = new KeyboardEvent("keydown", {key : "q", char : "q", shiftKey: false, keyCode:81,bubbles : true, cancelable : true});
//      document.getElementById('container')
//      document.dispatchEvent(keyEvent);
//      }
   
});