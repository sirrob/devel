
function userminilogin()
{
    $('#login-form').submit();
} 

$(function(){
    $('form.jqTransform').jqTransform();
    $('form.niceform').jqTransform();
    //$('form').jqTransform();
    
            
    //focus
    $('#login-input').focus(function(){
        if($('#login-input').val()==' - enter your e-mail - ') $('#login-input').val('');
    });

    $('#passowrd-input').focus(function(){
        if($('#passowrd-input').val()=='**********') $('#passowrd-input').val('');
    });

    $('#rejmail').focus(function(){
        if($('#rejmail').val()==' - enter your e-mail - ') $('#rejmail').val('');
    });

    $('#rejpass').focus(function(){
        if($('#rejpass').val()=='**********') $('#rejpass').val('');
    });
    
    $('#ko_email').focus(function(){
        if($('#ko_email').val()=='enter...') $('#ko_email').val('');
    });

    $('#ko_year').focus(function(){
        if($('#ko_year').val()=='RRRR') $('#ko_year').val('');
    });
   
    $('#ko_month').focus(function(){
        if($('#ko_month').val()=='MM') $('#ko_month').val('');
    });
    
    $('#ko_day').focus(function(){
        if($('#ko_day').val()=='DD') $('#ko_day').val('');
    });
    
//    $('#ol_haslo').focus(function(){
//        if($('#ol_haslo').val()=='write...') $('#ol_haslo').val('');
//    });
//    
//    $('#ko_haslo').focus(function(){
//        if($('#ko_haslo').val()=='write...') $('#ko_haslo').val('');
//    });
//
//    $('#re_haslo').focus(function(){
//        if($('#re_haslo').val()=='write...') $('#re_haslo').val('');
//    });
                
    $('#ko_nazwa').focus(function(){
        if($('#ko_nazwa').val()=='enter...') $('#ko_nazwa').val('');
    });

    $('#ko_firma').focus(function(){
        if($('#ko_firma').val()=='enter...') $('#ko_firma').val('');
    });
    
    $('#ko_nip').focus(function(){
        if($('#ko_nip').val()=='enter...') $('#ko_nip').val('');
    });
    
    $('#ko_miasto').focus(function(){
        if($('#ko_miasto').val()=='enter...') $('#ko_miasto').val('');
    });
    
    $('#ko_kod').focus(function(){
        if($('#ko_kod').val()=='enter...') $('#ko_kod').val('');
    });
    
    $('#ko_ulica').focus(function(){
        if($('#ko_ulica').val()=='enter...') $('#ko_ulica').val('');
    });

    $('#ko_ulica_dom').focus(function(){
        if($('#ko_ulica_dom').val()=='enter...') $('#ko_ulica_dom').val('');
    });

    $('#ko_ulica_lok').focus(function(){
        if($('#ko_ulica_lok').val()=='enter...') $('#ko_ulica_lok').val('');
    });
          
    $('#ko_telefon').focus(function(){
        if($('#ko_telefon').val()=='enter...') $('#ko_telefon').val('');
    });

    //blur

    $('#login-input').blur(function(){
        if($('#login-input').val()=='') $('#login-input').val(' - enter your e-mail - ');
    });

    $('#passowrd-input').blur(function(){
        if($('#passowrd-input').val()=='') $('#passowrd-input').val('**********');
    });

    $('#rejmail').blur(function(){
        if($('#rejmail').val()=='') $('#rejmail').val(' - enter your e-mail - ');
    });

//    $('#rejpass').blur(function(){
//        if($('#rejpass').val()=='') $('#rejpass').val('**********');
//    });

    $('#ko_email').blur(function(){
        if($('#ko_email').val()=='') $('#ko_email').val('write...');
    });

    $('#ko_year').blur(function(){
        if($('#ko_year').val()=='') $('#ko_year').val('RRRR');
    });

    $('#ko_month').blur(function(){
        if($('#ko_month').val()=='') $('#ko_month').val('MM');
    });

    $('#ko_day').blur(function(){
        if($('#ko_day').val()=='') $('#ko_day').val('DD');
    });

//    $('#ol_haslo').blur(function(){
//        if($('#ol_haslo').val()=='') $('#ol_haslo').val('write...');
//    });
//
//    $('#ko_haslo').blur(function(){
//        if($('#ko_haslo').val()=='') $('#ko_haslo').val('write...');
//    });
//
//    $('#re_haslo').blur(function(){
//        if($('#re_haslo').val()=='') $('#re_haslo').val('write...');
//    });

    $('#ko_nazwa').blur(function(){
        if($('#ko_nazwa').val()=='') $('#ko_nazwa').val('enter...');
    });

    $('#ko_firma').blur(function(){
        if($('#ko_firma').val()=='') $('#ko_firma').val('enter...');
    });

    $('#ko_nip').blur(function(){
        if($('#ko_nip').val()=='') $('#ko_nip').val('enter...');
    });

    $('#ko_miasto').blur(function(){
        if($('#ko_miasto').val()=='') $('#ko_miasto').val('enter...');
    });

    $('#ko_kod').blur(function(){
        if($('#ko_kod').val()=='') $('#ko_kod').val('enter...');
    });

    $('#ko_ulica').blur(function(){
        if($('#ko_ulica').val()=='') $('#ko_ulica').val('enter...');
    });

    $('#ko_ulica_dom').blur(function(){
        if($('#ko_ulica_dom').val()=='') $('#ko_ulica_dom').val('enter...');
    });

    $('#ko_ulica_lok').blur(function(){
        if($('#ko_ulica_lok').val()=='') $('#ko_ulica_lok').val('enter...');
    });

    $('#ko_telefon').blur(function(){
        if($('#ko_telefon').val()=='') $('#ko_telefon').val('enter...');
    });

    //fill empty
           
    if($('#login-input').val()=='') $('#login-input').val(' - enter your e-mail - ');
    if($('#passowrd-input').val()=='') $('#passowrd-input').val('**********');
    if($('#rejmail').val()=='') $('#rejmail').val(' - enter your e-mail - ');
    if($('#rejpass').val()=='') $('#rejpass').val('**********');
    if($('#ko_email').val()=='') $('#ko_email').val('enter...');
    if($('#ko_year').val()=='') $('#ko_year').val('RRRR');
    if($('#ko_month').val()=='') $('#ko_month').val('MM');
    if($('#ko_day').val()=='') $('#ko_day').val('DD');
//    if($('#ol_haslo').val()=='') $('#ol_haslo').val('write...');
//    if($('#ko_haslo').val()=='') $('#ko_haslo').val('write...');
//    if($('#re_haslo').val()=='') $('#re_haslo').val('write...');
    if($('#ko_nazwa').val()=='') $('#ko_nazwa').val('enter...');
    if($('#ko_firma').val()=='') $('#ko_firma').val('enter...');
    if($('#ko_nip').val()=='') $('#ko_nip').val('enter...');
    if($('#ko_miasto').val()=='') $('#ko_miasto').val('enter...');
    if($('#ko_kod').val()=='') $('#ko_kod').val('enter...');
    if($('#ko_ulica').val()=='') $('#ko_ulica').val('enter...');
    if($('#ko_ulica_dom').val()=='') $('#ko_ulica_dom').val('enter...');
    if($('#ko_ulica_lok').val()=='') $('#ko_ulica_lok').val('enter...');
    if($('#ko_telefon').val()=='') $('#ko_telefon').val('enter...');


    $('input#do2').click(function(){
        $('#dadres').animate({height: 240},400,function(){
            $('#dadres').css("overflow", "visible");
        });
    });

    $('input#do1').click(function(){
        $('#dadres').css("overflow", "hidden");
        $('#dadres').animate({height: 1},400,function(){
            $('#dadres').css("overflow", "hidden");
        });
    })
                        

    //option select - onchange
    $("#top-sort-type").change(function(){
        var str = window.location.toString();
        var idx = str.indexOf("?");

        if(idx >0) str = str.substr(0,idx);
        window.location=str+'?o='+el.lnk.innerHTML;
    })


    $('#top-page-items').change(function(){
        var str = window.location.toString();
        var idx = str.indexOf("?");
        if(idx >0) str = str.substr(0,idx);

        window.location=str+'?ppp='+el.value;
    });

});


//function gofilter(name,val)
function gofilter()
{
    setTimeout("$('#filtered').submit()",100);
}


function fkategoriana(val,url,param)
{
    if(val != '') window.location=url+'?kategoria='+val+param;
}

function fszspd(val)
{
    $.ajax({
        type: "POST",
        url: Purl+"processing.php",
        data: 'search_kat=' + val+'&ckatid='+ckatid,
        success: function(html){
            $("#wkategoria").html(html);
        },//szska
        complete: function(){
            $('select').jqTransSelect();
        }
    });
                    
    $.ajax({
        type: "POST",
        url: Purl+"processing.php",
        data: 'getProducts=' + val+'&spd='+val+'&ska='+$('#szska').val()+'&sro='+$('#szsro').val()+'&sna='+$('#szsna').val()+'&ckatid='+ckatid,
        success: function(html){
            $(".ramka").html(html);
        },
        complete: function(){
            tooltip_go();
            $('select').jqTransSelect();
        }
    });
}


function fszska(val)
{
    $.ajax({
        type: "POST",
        url: Purl+"processing.php",
        data: 'search_ro=' + val+'&search_rokat='+$('#szspd').val()+'&ckatid='+ckatid,
        success: function(html){
            $("#wrozmiar").html(html);
        },//szsro
        complete: function(){
            $('select').jqTransSelect();
        }
    });
                   
    $.ajax({
        type: "POST",
        url: Purl+"processing.php",
        data: 'getProducts=' + val+'&spd='+$('#szspd').val()+'&ska='+val+'&sro='+$('#szsro').val()+'&sna='+$('#szsna').val()+'&ckatid='+ckatid,
        success: function(html){
            $(".ramka").html(html);
        },
        complete: function(){
            tooltip_go();
            $('select').jqTransSelect();
        }
    });
}

function fszsro(val)
{           
    $.ajax({
        type: "POST",
        url: Purl+"processing.php",
        data: 'getProducts=' + val+'&spd='+$('#szspd').val()+'&ska='+$('#szska').val()+'&sro='+val+'&sna='+$('#szsna').val()+'&ckatid='+ckatid,
        success: function(html){
            $(".ramka").html(html);
        },
        complete: function(){
            tooltip_go();
        }
    });
}

$(document).ready(function(){
    $('.page').fadeIn(2000);
    
    jskoskadinne($('select[name=ko_skad]').val());
});

function jskoskadinne(val)
{
    if(val=='other')
    {
        $('#koskadinne').show();
        
    } else
    {
        $('#koskadinne').hide();
    }
}