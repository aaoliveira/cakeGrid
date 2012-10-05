    ChiliBook.recipeFolder = "chili/";
    ChiliBook.stylesheetFolder = "chili/";


    $(function(){
                $("a:first", ".menuv li.submenu", ".menuh li.submenu").addClass("seta");
                
                $(".menuv li.submenu, .menuh li.subv").each(function(){
                    var el = $('#' + $(this).attr('id') + ' ul:eq(0)');
                    
                    $(this).hover(function(){
                        el.show();
                    }, function(){
                        el.hide();
                    });
                });
            });