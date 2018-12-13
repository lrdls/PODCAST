$(document).ready(function(){

    $("#filter").val('');
    $("#filter-count").hide();

    $('audio').mediaelementplayer({
        features: ['playpause','progress','current','tracks','fullscreen']
    });

    $.fn.extend({
        toggleText: function(a, b){
            return this.text(this.text() == b ? a : b);
        }
    });

    function alltags() {
        var tags = document.getElementsByClassName('article'), i;
        for (i = 0; i < tags.length; i += 1) {
            tags[i].style.display = 'block';
        }
        $("#filter").val('');
        // $("#filter-count").text("Article(s) = "+nArticles);
        $("#filter-count").hide();
    }
    function clearHighlights() {
        $('.edittopic').each(function() {
            $(this).removeClass('edittopic-highlight');
        });   
    }
    function clearSearch() {
        $('.footer').show();
        clearHighlights(); 
    }
    function buttonUp(){
        var inputVal = $('.searchbox-input').val();
        inputVal = $.trim(inputVal).length;
        if( inputVal !== 0){
            $('.searchbox-icon').css('display','none');
        } else {
            $('.searchbox-input').val('');
            $('.searchbox-icon').css('display','block');
        }
    }

    $('.edittopic').click(function(e){
        $(this).next().slideToggle();
        $(this).toggleText("[ plus d'infos ]", "[   fermer   ]");
        e.preventDefault();
    });

    $('.navbar-click').click(function(e){
        var tag = this.id;
        var tagsA = document.getElementsByClassName('article'), i;
        for (i = 0; i < tagsA.length; i += 1) {
            tagsA[i].style.display = 'none';
        }
        var tags = document.getElementsByClassName(tag), i;
        for (i = 0; i < tags.length; i += 1) {
            tags[i].style.display = 'block';
        }
        e.preventDefault();
    });

    var nArticles = 0;
    $("div").each(function(){
        if ( $( this ).hasClass( "article" ) ) {
            nArticles++;
        }
    });

    $("#filter").keyup(function(e){
        $('.footer').hide();
        $('.edittopic').each(function() {
            $(this).removeClass('edittopic-highlight');
        });
        var filter = $(this).val(), count = 0;
        $("div").each(function(){
            if ( $( this ).hasClass( "article" ) ) {
                if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                    $(this).fadeOut();
                } else {
                    $(this).show();
                    var id_edittopic = "edittopic-"+$(this).attr('id');
                      $('#'+id_edittopic+'').each(function() {
                        $(this).addClass('edittopic-highlight');
                      });
                    e.preventDefault();
                    count++;
                }
            }
        });
        var numberItems = count;
        $("#filter-count").show();
        $("#filter-count").text("Article(s) = "+count+" / " + nArticles);
        if(filter.length==0){
            clearSearch();
        }
    });

    $(".icon-search").click(function(e){
        $("#formX").slideToggle("500");
        e.preventDefault();
    });

    $(".icon-search-clear").click(function(e){
        clearSearch();
        if($("#filter").val().length == 0){
            $("#formX").slideToggle("500");
        }
        alltags();   
        e.preventDefault();
    });  

    $('#formX').hover(
         function () {
           $(this).css({"background-color":"deepskyblue"});
         }, 
         function () {
           $(this).css({"background-color":"#1995cc"});
         }
     );

    var submitIcon = $('.searchbox-icon');
    var inputBox = $('.searchbox-input');
    var searchBox = $('.searchbox');
    var isOpen = false;

    submitIcon.click(function(){
        if(isOpen == false){
            searchBox.addClass('searchbox-open');
            inputBox.focus();
            isOpen = true;
        } else {
            searchBox.removeClass('searchbox-open');
            inputBox.focusout();
            isOpen = false;
        }
    });  

    submitIcon.mouseup(function(){
        return false;
    });

    searchBox.mouseup(function(){
        return false;
    });

    $(document).mouseup(function(){
        if(isOpen == true){
            $('.searchbox-icon').css('display','block');
            submitIcon.click();
        }
    });

});