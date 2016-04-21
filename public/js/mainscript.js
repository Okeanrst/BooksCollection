function flashMessage(mess, error, success) {
    if (error !== '') {
        mess.html(error);           
        mess.addClass("error");
        mess.removeClass("hidden");
        setTimeout(function() {
            mess.addClass("hidden");
            mess.removeClass("error");
        }, 9000)
    } else if (success !== '') {
        mess.html(success);         
        mess.addClass("success");
        mess.removeClass("hidden");
        setTimeout(function() {
            mess.addClass("hidden");
            mess.removeClass("success");
        }, 3000);
    }
}

function doAction(e) {            
    e.preventDefault();
    var target = $(e.target);
    var parentTr = target.parents("tr")[0];           
    var id = parentTr.dataset.id;
            //var url = 'ajax' + target.attr('href') + String.fromCharCode(47) + id
            //var href = target.attr('href');
            //var url = String.fromCharCode(47) + 'books'+String.fromCharCode(47) + 'ajax' + href.substring(7);            
    $('#cover').removeClass('hidden').addClass('cover');           
    $('#ajaxcontainer').removeClass('hidden').addClass('ajaxpage');            
            //var ajaxform = $('form[name^="{formName}"]')[0];
            //var ajaxform = document.forms.deleteauthor;
    ajaxform = document.forms[formName];            
    $('[name^="id"]', $(ajaxform)).val(id);
    var cancel = $('[name^="cancel"]', $(ajaxform));             
    cancel.click(function(e) {               
        $('#ajaxcontainer').removeClass('ajaxpage').addClass('hidden');
        $('#cover').removeClass('cover').addClass('hidden');
        cancel.unbind("click");
        $(ajaxform).unbind("submit");
        e.stopPropagation();               
        return;
    });

    $(ajaxform).submit(function(e) { 
        cancel.unbind("click");
        $(e.target).unbind("submit");
        var formData = new FormData(ajaxform);                
        $.ajax({
            type: "POST",
            processData: false,
            contentType: false,
            url: url, 
            data: formData,
            success: function(result){                        
                $('#ajaxcontainer').removeClass('ajaxpage').addClass('hidden');
                $('#cover').removeClass('cover').addClass('hidden');
                var data = JSON.parse(result);
                flashMessage(mess, data['error'], data['success']);
                if (reload) {
                    window.location = curUrl;
                }
            },
            error: function(result) {
                console.log('error'+ result);
            }                    
        });
            e.stopPropagation();
            e.preventDefault();
    });              
}