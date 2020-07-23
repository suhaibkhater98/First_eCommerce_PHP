$(function(){

    $('.login-page h1 span').click(function(){
        $(this).addClass('active').siblings().removeClass('active')
        $('.login-page form').hide()
        $('.' + $(this).data('class')).fadeIn(200)
    })
    $('[placeholder]').focus(function(){
        $(this).attr('data-text' , $(this).attr('placeholder'))
        $(this).attr('placeholder' , '')
    }).blur(function(){
        $(this).attr('placeholder' , $(this).attr('data-text'))
    })

    // add star for required filled

    $('input').each(function(){
        if($(this).attr('required') === 'required'){
            $(this).after('<span class="asterisk">*</span>')
        }
    })

    let passFiled = $(".password");
    $(".show-pass").hover(function(){
        passFiled.attr('type' , 'text')
    } , function(){
        passFiled.attr('type' , 'password')
    })


    //confirm message
    $(".confirm").click(function(){
        return confirm("Are You sure ?");
    })

    $(".live").keyup(function(){
        $($(this).data('class')).text($(this).val())
    })


})