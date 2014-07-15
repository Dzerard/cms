     
     function updateVisibility() {
         
        $('a.change-visibility').on('click', function(){
           
           var button = $(this).children();
           var cls = button.attr('class');
           var url = $(this).attr('href');
          
           
           button.removeClass(cls);
           button.addClass('fa fa-spinner fa-spin');
         
           $.ajax({

               type : 'GET',                       
               url  : url,
               
               success : function(result) {
                    
                    button.removeClass('fa fa-spinner fa-spin');
                    if(cls === 'fa fa-check-square-o') {
                        button.addClass('fa fa-square-o');
                    } else {
                        button.addClass('fa fa-check-square-o');
                    }

               },
               error : function(xmlHttpRequest, textStatus, errorThrown) {                                   

                    console.log(errorThrown);
              }
           }); 
           
           return false;
           
        });
     }
     
     function showTabsHash() {
         
        $('#navDesign li a').on('click', function(){
           
           history.pushState('', '', $(this).attr('href'));         
           
        });
                
        var pathname = window.location.hash;    
        
        if(window.location.hash.length > 0 && $('body').find(pathname).length > 0) {
            
            $('#TabContent').children('.tab-pane').removeClass('active');
            $('#navDesign').children('li').removeClass('active');
            $(pathname).addClass('active');
            
            $.each($('#navDesign li'), function( index, value ) {
               if($(value).children('a').attr('href') === pathname) {
                   $(value).addClass('active');
               }               
            });
            
                    
        }
     }
     
    //funkcja dla admin/view
    function resetActions() {
        
        function checkIfFogInputChecked() {
            var oFog  = $('#designFogSwitch');
            var oBlur = $('#designFogBlur');
            var oFogSettings = $('.view-fog-settings');
           
            if(oFog.is(':checked')) {
                oBlur.attr('required',true);
            }
            
            oFog.click(function(){
                
                if($(this).is(':checked') === true) {
                    oFogSettings.fadeIn();
                    oBlur.attr('required',true);
                } else {
                    oFogSettings.fadeOut();
                    oBlur.attr('required',false);
                }
            });
            return false;
        }
        
       
        
        function resetView() {
            $('.resetView').click(function(){
                var oButton = $(this);
                oButton.button('loading');

                setTimeout(function(){
                    oButton.button('reset');
                    alert('spróbuj później');
                },2000);
                return false;
            });
        }
        checkIfFogInputChecked();
        resetView();        
        triggerPanelHidding();
        renderSampleVizualization();
        colorPicker($('#designFogColor'), $('.colorPick'),$('.pickerHolder') );
        colorPicker($('#designPlaneColor'), $('.colorPickPlane'),$('.pickerHolderPlane') );
        showTabsHash();
        
    }

    function renderSampleVizualization(load) {
         $('body').on('click', '.ajaxShowVisualization', function() {
             
             var oButton = $(this);
             oButton.button('loading');
             
             setTimeout(function(){
                 oButton.button('reset');
                 alert('wystapił błąd, spróbuj później');
             },2000);
            
             return false;
         });
        
    }

    function showOnSelectTextarea() {
        
        var oSize  = $('.visulaziation-element-size');
        var oFile  = $('.visualization-element-file');
        var oScale = $('.visulaziation-element-scale');
        
        $('.visualization-element-radio').on('click', 'input[name=visualizationElement]', function() {
            
            if(oFile.hasClass('hidden')) { oFile.hide();   oFile.removeClass('hidden');  }
            if(oSize.hasClass('hidden')) { oSize.hide();   oSize.removeClass('hidden');  } 
            
            var radioButton = $(this);
            
            if(radioButton.prop('checked') === true && radioButton.val() === 'file') {
               
                oSize.slideUp(function(){
                    oFile.slideDown(); 
                    oScale.slideDown(); 
                });
                
            } else if(radioButton.prop('checked') === true && radioButton.val() === 'other') {
                oSize.slideUp(function(){
                    oFile.slideUp(); 
                    oScale.slideUp(); 
                });
            }            
            else {
                oFile.slideUp(function(){
                    oSize.slideDown();
                    oScale.slideDown(); 
                });                   
            }
        });
        
       
        return false;
    }
    
    //funkcja do sprawdzania czy jest wolne miejsce 
    function checkGrid() {
            var title = $('.grid-chooser .btn-info').attr('title'); // globalna nazwa firmy 
            
            $('.grid-chooser').on('click','button', function(e) {
                
                e.preventDefault();                
                var button = $(this);
                
                //blokada dla juz zajetych miejsc
                if(button.hasClass('btn-inverse')) {
                    
                    var modal = $('#information-modal').modal();
                    modal.find('.companyTitle').text(button.attr('data-original-title'));         
                    return;
                }
                if(button.hasClass('btn-info')) {                  
                            
                    return;
                }
               
                var hidden = $('.grid-chooser button.btn-info');
                hidden.html('<i class="fa fa-spinner fa-spin"></i>');
                button.html('<i class="fa fa-spinner fa-spin"></i>');
                
                //tutaj powinien byc ajax do ponownego sprawdzania
                setTimeout(function(){
                    hidden = $('.grid-chooser button.btn-info');
                    hidden.html('<i class="fa fa-square-o"></i>').removeClass('btn-info').attr('data-original-title', hidden.attr('value'));
                   
                   
                    if( typeof(title) !== 'undefined') {
                        button.html('<i class="fa fa-check-square-o"></i>').attr('data-original-title', title).addClass('btn-info');
                    } else {
                        button.html('<i class="fa fa-check-square-o"></i>').attr('data-original-title', 'Twoje miejsce').addClass('btn-info');
                    }
                    

                    $('body').find('#offerNumber').attr('value',button.attr('value'));
                },1000);
                        
                
                return false;
            });
            
        }
    
    function settingsActions() {
        //modal usuwania translacji
        $(document).on('click', '.btn.btn-danger.btn-sm', function(){                

           $('#myModalDelFile').find('#delLanguage').html($(this).attr('id'));
           $('#myModalDelFile').find('#languageNameInput').val($(this).attr('rel'));

        });
        $(document).on('click', '.editLangFile', function(){                

           $('#myModalEditFile').find('#languageName').val($(this).attr('id'));

        });
        
        $('a,button,#transaltionFilePo').tooltip();    //tooltip
        
        //serviceMail
        $('#serviceForm').on('blur', '#emailTitle, #emailDesc', function(){
        
            if( !$(this).val()) { 

                 $('#sendServiceMailButton').attr("disabled", true);         
                 $('#sendServiceMailButton').tooltip('hide');  
            }
            else {
                 $('#sendServiceMailButton').attr("disabled", false);                 
                 $('#sendServiceMailButton').attr('data-original-title', 'Wyślij wiadomość');
            }
        });
    }
    
    function triggerPanelHidding() {
        //zwijanie paneli
        $('.panel').on('click', '.panel-heading', function(){

           var panBody = $( this ).parent().children('.panel-body');

           if(panBody.is(':visible')) {
               panBody.slideUp();
               $('i',this).hide().removeClass('glyphicon-chevron-up').delay(100).addClass('glyphicon-chevron-down').fadeIn();
           } else {
               panBody.slideDown();
               $('i',this).hide().removeClass('glyphicon-chevron-down').delay(100).addClass('glyphicon-chevron-up').fadeIn();
           }    
        });
    }
    
    function fireFancybox(param) {
        
        $("a.image-box").fancybox({
		'transitionIn'	:	'elastic',
		'transitionOut'	:	'elastic',
		'speedIn'		:	600, 
		'speedOut'		:	200, 
		
                'hideOnContentClick': true
	});
    }
    
    function fireTinyMCE(param) {
        tinyMCE.init({
                // General options
                language : 'pl',
                mode : "textareas",
                height: 175,                
                //width : 700
                plugins: [
                    "advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste "
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"

        });
    }    
    
    function numberControl(oInput, oItems) {
        
        //tylko liczby
        $(''+oItems+' .form-control').keydown(function(event) {  
          if ( $.inArray(event.keyCode,[46,8,9,27,13,190]) !== -1 ||
                 // Allow: Ctrl+A
                (event.keyCode == 65 && event.ctrlKey === true) || 
                 // Allow: home, end, left, right
                (event.keyCode >= 35 && event.keyCode <= 39)) {
                     // let it happen, don't do anything
                     return;
            }
            else {
                // Ensure that it is a number and stop the keypress
                if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                    event.preventDefault(); 
                }   
            }
        }); 
   
        $('body').on('change', ""+oItems+" .form-control", function(event) {

            var vElementSize = oInput; 
            var number = $(this); 

            if(parseInt(number.val()) === 0 || number.val().length === 0  || parseInt(number.val()) > 1000 ) {number.val(1);}              
            vElementSize.val(refreshValueElementSize(oItems).slice(0,-1));

            return false;

        });
    }
   
    function refreshValueElementSize(oItems) {

         var oValue ='';
         $.each($(""+oItems+" .form-control"), function( index, value ) {
                oValue += $(value).val()+',';     
         });

         return oValue;
    }
    
    function colorPicker(oInput, oColorPick, oColorHolder) {
      
        var defaultColor = oInput; //pobieranie 
        var colorPick    = oColorPick; 

        colorPick.css('background', defaultColor.val()); //setowanie backgroundu ;)
        
        oColorHolder.ColorPicker({
                    color: defaultColor.val(),
                    flat: true,
            onChange: function (hsb, hex, rgb) {
                    colorPick.css('background', '#' + hex);
                    defaultColor.val('#' +hex);
            },

            onSubmit: function(hsb, hex, rgb) {
                    colorPick.css('background', '#' + hex);
                    defaultColor.val('#' +hex);
            }
        });

        var widt = false;
        $('.visualizationColorBox').bind('click', function(e) {
                var that = $(e.currentTarget);               
//                if(that.hasClass('activeClick')) {
//                    that.removeClass('activeClick');
//                    that.tooltip('destroy').attr('title','zmień kolor').tooltip();                    
//                } else {
//                    that.addClass('activeClick');
//                    that.tooltip('destroy').attr('title','kliknij aby zamknąć').tooltip();            
//                }
                if(widt) {
                    oColorHolder.fadeOut('fast'); 
                    that.removeClass('activeClick');
                    that.tooltip('destroy').attr('title','zmień kolor').tooltip();    
                } else {
                    oColorHolder.fadeIn('fast');
                    that.addClass('activeClick');
                    that.tooltip('destroy').attr('title','kliknij aby zamknąć').tooltip();      
                }
                //$('.pickerHolder').stop().animate({height: widt ? 0 : 173}, 500);                
                widt = !widt;
                return false;
        });
    }
    
    function hideAlertAfterTime() {
        var alert = $(document).find('.alert.alert-info');
        if(alert.length >0 ) {
            
            alert.hide();            
            alert.slideDown();
            
            setTimeout(function(){
                alert.slideUp();
            },4000);
        }
    }
   
    
jQuery(document).ready(function($) { 
//    $('body').hide();
//    $('.navbar.navbar-default.navbar-fixed-top').hide();
//    $('.navbar.navbar-default.navbar-fixed-top').hide('fast');
//    $('.container.main').hide();
//    
//    setTimeout( function() {
//        $('body').fadeIn(function(){
//            $('.navbar.navbar-default.navbar-fixed-top').fadeIn('fast',function(){
//                $('.container.main').fadeIn('fast');
//            });
//        });
//    },1);
    
   
    
 //   $('.container').fadeIn();
   
    // global actions fire
    hideAlertAfterTime();
    //to przeniesc do akcji editOffer
    checkGrid();
    $('[rel=tooltip]').tooltip();
    
    
    $('.panel-default .dropdown-menu').on('click', 'li', function(e) {
       
       $('.panel-default .dropdown-menu li').removeClass('active');
       var menuItem = $(this);
       menuItem.addClass('active');
       $('.search-form input[name=c]').val($(e.target).attr('rel'));
    });
    
 });   