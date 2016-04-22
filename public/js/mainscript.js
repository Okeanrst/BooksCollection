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

function newAction(e) {
    e.preventDefault();
    var target = $(e.target);    
    var url = target.attr('href').slice(7);
    posSlesh = url.indexOf(String.fromCharCode(47));
    if (~posSlesh) {
        url = url.slice(0, posSlesh);
    }
    var formName = url;
    ajaxform = document.forms[formName];    
    url = String.fromCharCode(47) + 'books' + String.fromCharCode(47) + 'ajax' +url;
    $('#cover').removeClass('hidden').addClass('cover');
    var ajaxpage = $('#ajaxnew');
    var cancel = addCancel(ajaxpage);
    ajaxpage.removeClass('hidden').addClass('ajaxpage');       
    cancel.click(function(){
        ajaxpage.removeClass('ajaxpage').addClass('hidden');
        $('#cover').removeClass('cover').addClass('hidden');        
        cancel.remove();
        $(ajaxform).unbind("submit");
        $('input[type!="submit"]', $(ajaxform)).each(function() {
            this.value = '';
        });
    });

    $(ajaxform).submit(function(e) { 
        e.stopPropagation();
        e.preventDefault();
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
                var data = JSON.parse(result);
                $(ajaxpage).removeClass('ajaxpage').addClass('hidden');
                $('#cover').removeClass('cover').addClass('hidden');             
                $('input[type!="submit"]', $(ajaxform)).each(function() {
                    this.value = '';
                });
                flashMessage(mess, data['error'], data['success']);
                if (reload) {
                    window.location.reload();
                }
            },
            error: function(result) {
                console.log(result);                
                cancel.click(function(){
                    ajaxpage.removeClass('ajaxpage').addClass('hidden');
                    $('#cover').removeClass('cover').addClass('hidden');                    
                    cancel.remove();
                    $(ajaxform).unbind("submit");
                    $('input[type!="submit"]', $(ajaxform)).each(function() {
                        this.value = '';
                    });                    
                });
            }                    
        });        
    });    
}

function editAction(e) {
    e.preventDefault();
    var target = $(e.target);    
    var url = target.attr('href').slice(7);
    posSlesh = url.indexOf(String.fromCharCode(47));
    if (~posSlesh) {
        url = url.slice(0, posSlesh);
    }
    var formName = url;
    var parentTr = target.parents("tr")[0];           
    var id = parentTr.dataset.id;
    
    ajaxform = document.forms[formName];    
    url = String.fromCharCode(47) + 'books' + String.fromCharCode(47) + 'ajax' +url;
    $('#cover').removeClass('hidden').addClass('cover');
    var ajaxpage = $('#ajaxedit');
    if (isFinite(id)) {
        $.ajax({
            type: "POST",
            //processData: false,
            //contentType: false,
            url: url, 
            data: {id: id},
            success: function(result){                
                var cancel = addCancel(ajaxpage);
                var fields = [];
                cancel.click(function(){
                    ajaxpage.removeClass('ajaxpage').addClass('hidden');
                    $('#cover').removeClass('cover').addClass('hidden');                    
                    cancel.remove();
                    $(ajaxform).unbind("submit");
                    for (property in fields) {
                        var elem = ajaxform[property];
                        elem.value = '';
                    }
                });                
                var data = JSON.parse(result);                                               
                if (!isFinite(data['id'])) {
                    $('#cover').removeClass('cover').addClass('hidden');
                    cancel.remove();                    
                    return;
                }                
                for (property in data) {
                    var elem = ajaxform[property];
                    elem.value = data[property];
                    fields.push(property);
                }
                $(ajaxpage).removeClass('hidden').addClass('ajaxpage');                

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
                            $(ajaxpage).removeClass('ajaxpage').addClass('hidden');
                            $('#cover').removeClass('cover').addClass('hidden');                            
                            var data = JSON.parse(result);
                            for (property in fields) {
                                var elem = ajaxform[property];
                                elem.value = '';
                            }
                            flashMessage(mess, data['error'], data['success']);
                            if (reload) {
                                window.location.reload();
                            }
                        },
                        error: function(result) {
                            console.log(result);
                            cancel.click(function(){
                                ajaxpage.removeClass('ajaxpage').addClass('hidden');
                                $('#cover').removeClass('cover').addClass('hidden');                                
                                cancel.remove();
                                $(ajaxform).unbind("submit");
                                for (property in fields) {
                                    var elem = ajaxform[property];
                                    elem.value = '';
                                }
                            });
                        }                    
                    });
                    e.stopPropagation();
                    e.preventDefault();
                });

                //flashMessage(mess, data['error'], data['success']);
                if (reload) {
                    //window.location.reload();
                }
            },
            error: function(result) {
                $('#cover').removeClass('cover').addClass('hidden');                
                console.log(result);
            }                    
        });
    }
}

function deleteAction(e) {            
    e.preventDefault();
    var target = $(e.target);
    var parentTr = target.parents("tr")[0];           
    var id = parentTr.dataset.id;
    var url = target.attr('href').slice(7);
    posSlesh = url.indexOf(String.fromCharCode(47));
    if (~posSlesh) {
        url = url.slice(0, posSlesh);
    }
    var formName = url;    
    url = String.fromCharCode(47) + 'books' + String.fromCharCode(47) + 'ajax' +url;    
            //var href = target.attr('href');
            //var url = String.fromCharCode(47) + 'books'+String.fromCharCode(47) + 'ajax' + href.substring(7);            
    $('#cover').removeClass('hidden').addClass('cover');           
    $('#ajaxdelete').removeClass('hidden').addClass('ajaxpage');            
            //var ajaxform = $('form[name^="{formName}"]')[0];
            //var ajaxform = document.forms.deleteauthor;
    ajaxform = document.forms[formName];            
    $('[name^="id"]', $(ajaxform)).val(id);
    var cancel = $('[name^="cancel"]', $(ajaxform));             
    cancel.click(function(e) {               
        $('#ajaxdelete').removeClass('ajaxpage').addClass('hidden');
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
                $('#ajaxdelete').removeClass('ajaxpage').addClass('hidden');
                $('#cover').removeClass('cover').addClass('hidden');
                var data = JSON.parse(result);
                flashMessage(mess, data['error'], data['success']);
                if (reload) {
                    window.location.reload();
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

function addCancel(elem) {
    var btn = '<button class="btn btn-primary">Cancel</button>';
    $(elem).append(btn);
    return $('button', elem);    
}