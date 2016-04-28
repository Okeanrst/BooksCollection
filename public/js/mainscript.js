function flashMessage(mess, error, success) {
    if (error !== '') {
        console.log(error);
        mess.html(error);           
        mess.addClass("error");
        mess.removeClass("hidden");
        setTimeout(function() {
            mess.addClass("hidden");
            mess.removeClass("error");
        }, 9000)
    } else if (success !== '') {
        console.log(error);
        mess.html(success);         
        mess.addClass("success");
        mess.removeClass("hidden");
        setTimeout(function() {
            mess.addClass("hidden");
            mess.removeClass("success");
        }, 3000);
    }
}

function loadPage(e) {
    e.preventDefault();
    $('#cover').removeClass('hidden').addClass('cover');
    $('#cover').click(function() {
        $('#cover').removeClass('cover').addClass('hidden');
        $('#ajaxpagefile').removeClass('ajaxpage').addClass('hidden');        
    });
    var target = e.target;
    var url = $(target).attr('href');       
    var type = target.dataset.type;    
    $('embed', $('#ajaxpagefile')).attr('src', url);   
    $('#ajaxpagefile').removeClass('hidden').addClass('ajaxpage');
    
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
                try {
                    var data = JSON.parse(result);                    
                } catch (e) {
                    var data = [];
                    flashMessage(mess, 'Error JSON', '');
                    data['error'] = '';
                    data['success'] = '';                                                                
                } 
                flashMessage(mess, data['error'], data['success']);                                                       
                ajaxpage.removeClass('ajaxpage').addClass('hidden');
                $('#cover').removeClass('cover').addClass('hidden');                    
                cancel.remove();                            
                $('input[type!="submit"]', $(ajaxform)).each(function() {
                    this.value = '';
                });
                $('option').each(function(){
                    this.selected = false;
                });
                if (reload) {
                    window.location.reload();
                } else {
                    if (data['success']) {
                        switch (formName) {
                        case 'newbook':
                            line = prepareBookLine($('#line')[0], data['data'], false);
                        case 'newauthor':
                            line = prepareAuthorLine($('#line')[0], data['data'], false);
                            break
                        case 'newrubric':                    
                            line = prepareRubricLine($('#line')[0], data['data'], false);
                            break
                    }
                        $(line).removeClass('hidden');
                        $('.table').append(line);                    
                    } 
                }
            },
            error: function(result) {
                console.log(result.responseText);                            
                flashMessage(mess, 'AJAX error', '');
                ajaxpage.removeClass('ajaxpage').addClass('hidden');
                $('#cover').removeClass('cover').addClass('hidden');                    
                cancel.remove();                            
                $('input[type!="submit"]', $(ajaxform)).each(function() {
                    this.value = '';
                });
            }                    
        });        
    });    
}

function editAction(e) {
    e.preventDefault();
    var target = e.target;    
    var url = $(target).attr('href').slice(7);
    posSlesh = url.indexOf(String.fromCharCode(47));
    if (~posSlesh) {
        url = url.slice(0, posSlesh);
    }
    var formName = url;
    var parentTr = $(target).parents("tr")[0];           
    var id = target.dataset.id;
    var selectElem = [];
    
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
                    $('input[type!="submit"]', $(ajaxform)).each(function() {
                        this.value = '';
                    });
                    while (selectElem.length > 0) {
                        popped = selectElem.pop();
                        popped.selected = false;                                                
                    }
                });                
                try {
                    var data = JSON.parse(result);                    
                } catch (e) {
                    var data = [];
                    flashMessage(mess, 'Error JSON', '');
                    $(cancel).click();
                    return;
                }
                if (data.error || !data.success) {
                    flashMessage(mess, data.error, '');
                    $(cancel).click();
                    return;
                }                                               
                data = data.success;
                if (!isFinite(data['id'])) {
                    flashMessage(mess, 'id is not digit', '');
                    $(cancel).click();                    
                    return;
                }               
                    
                for (property in data) {                    
                    var elem = ajaxform[property];                    
                    if (-1 !== property.indexOf('[]')) {
                        var values = data[property].toString().split(',');
                        $("option", $(elem)).each(function( index ) {
                            for (var i = 0; i < values.length; i++) {
                                if (this.value == values[i]) {
                                    this.selected = true;
                                    selectElem.push(this);                                    
                                } 
                            }                            
                        });
                    } else {                        
                        elem.value = data[property];
                        fields.push(property);
                    }                        
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
                            try {
                                var data = JSON.parse(result);                    
                            } catch (e) {
                                flashMessage(mess, 'Error JSON', '');
                                var data = [];
                                data['error'] = '';
                                data['success'] = '';                                                                
                            } 
                            flashMessage(mess, data['error'], data['success']);                                                       
                            ajaxpage.removeClass('ajaxpage').addClass('hidden');
                            $('#cover').removeClass('cover').addClass('hidden');                    
                            cancel.remove();                            
                            $('input[type!="submit"]', $(ajaxform)).each(function() {
                                this.value = '';
                            });                            
                            
                            while (selectElem.length > 0) {
                                popped = selectElem.pop();
                                popped.selected = false;
                            }

                            if (reload) {
                                 window.location.reload();
                            } else {
                                if (data['success']) {
                                    switch (formName) {
                                    case 'editbook':
                                        line = prepareBookLine(parentTr, data['data'], true);
                                    case 'editauthor':
                                        line = prepareAuthorLine(parentTr, data['data'], true);
                                        break
                                    case 'editrubric':                    
                                        line = prepareRubricLine(parentTr, data['data'], true);
                                        break
                                    }                                                    
                                } 
                            } 
                        },
                        error: function(result) {
                            console.log(result.responseText);                            
                            flashMessage(mess, 'AJAX error', '');
                            ajaxpage.removeClass('ajaxpage').addClass('hidden');
                            $('#cover').removeClass('cover').addClass('hidden');                    
                            cancel.remove();                            
                            $('input[type!="submit"]', $(ajaxform)).each(function() {
                                this.value = '';
                            });
                            while (selectElem.length > 0) {
                                popped = selectElem.pop();
                                popped.selected = false;                                
                            }
                        }                    
                    });
                    e.stopPropagation();
                    e.preventDefault();
                });
            },
            error: function(result) {
                $('#cover').removeClass('cover').addClass('hidden');                
                console.log(result.responseText);
            }                    
        });
    }
}

function deleteAction(e) {            
    e.preventDefault();
    var target = e.target;
    var parentTr = $(target).parents("tr")[0];           
    var id = target.dataset.id;
    var url = $(target).attr('href').slice(7);
    posSlesh = url.indexOf(String.fromCharCode(47));
    if (~posSlesh) {
        url = url.slice(0, posSlesh);
    }
    var formName = url;    
    url = String.fromCharCode(47) + 'books' + String.fromCharCode(47) + 'ajax' +url; 
                
    $('#cover').removeClass('hidden').addClass('cover');           
    $('#ajaxdelete').removeClass('hidden').addClass('ajaxpage');            
    
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
                try {
                    var data = JSON.parse(result);                    
                } catch (e) {
                    flashMessage(mess, 'Error JSON', '');
                    var data = [];
                    data['error'] = '';
                    data['success'] = '';                                                                
                } 
                flashMessage(mess, data['error'], data['success']);
                if (data['success'] && parentTr !== undefined) {
                    $(parentTr).remove();
                }                
                if (parentTr === undefined && urlRedirect !== undefined) {
                    window.location = urlRedirect;
                }
                if (reload) {
                    window.location.reload();
                }
            },
            error: function(result) {
                console.log(result.responseText);                            
                flashMessage(mess, 'AJAX error', '');
                $('#ajaxdelete').removeClass('ajaxpage').addClass('hidden');
                $('#cover').removeClass('cover').addClass('hidden');
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

function prepareBookLine(line, data, isEdit) {
    var num = 0;
    $('td[name="num"]').each(function() {
        num = ($(this).text() > num) ? $(this).text() : num;
    });
    var title = $('td[name="title"]', $(line))[0];      
    $('a', $(title))[0].dataset.id = data['id'];
    $('a', $(title)).attr('href', data['title']['href']).text('').text(data['title']['title']);
    var author = $('td[name="author"]', $(line));    
    $('a', $(author)).attr('href', data['author']['href']).text('').text(data['author']['value']);    
    var dataRubric = data['rubric'];
    $('td[name="rubric"]', $(line)).text('');
    for (property in dataRubric) {
        var a = document.createElement('a');
        $(a).attr('href', dataRubric[property]['href']).text('').text(dataRubric[property]['value']);
        $('td[name="rubric"]', $(line)).append(a);
    }
    $('img', $(line)).attr('src', data['img']);
    var view = $('td[name="view"]', $(line))[0];
    
    $('a', $(view)).attr('href', data['view']['href']);
    var a = $('a', $(view))[0];
    a.dataset.type = data['view']['type'];
    var edit = $('td[name="edit"]', $(line));
    $('a', $(edit)).attr('href', data['edit']);
    var del = $('td[name="delete"]', $(line));
    $('a', $(del)).attr('href', data['del']);
    if (!isEdit) {
        num = num*1+1;
        $('td[name="num"]', $(line)).text('').text(num);
        var aedit = $('a', $(edit))[0];
        aedit.dataset.id = data['id'];
        var adel = $('a', $(del))[0];
        adel.dataset.id = data['id'];
    }    
    return line;
}

function prepareAuthorLine(line, data, isEdit) {
    var num = 0;
    $('td[name="num"]').each(function() {
        num = ($(this).text() > num) ? $(this).text() : num;
    });        
    var author = $('td[name="author"]', $(line));    
    $('a', $(author)).attr('href', data['author']['href']).text('').text(data['author']['value']);
    $('td[name="name"]', $(line)).text('').text(data['name']);
    var edit = $('td[name="edit"]', $(line));
    $('a', $(edit)).attr('href', data['edit']);
    var del = $('td[name="delete"]', $(line));
    $('a', $(del)).attr('href', data['del']);
    if (!isEdit) {
        num = num*1+1;
        $('td[name="num"]', $(line)).text('').text(num);           
        var aedit = $('a', $(edit))[0];
        aedit.dataset.id = data['id'];
        var adel = $('a', $(del))[0];
        adel.dataset.id = data['id'];
    }    
    return line;
}

function prepareRubricLine(line, data, isEdit) {
    var num = 0;
    $('td[name="num"]').each(function() {
        num = ($(this).text() > num) ? $(this).text() : num;
    });        
    var rubric = $('td[name="rubric"]', $(line));    
    $('a', $(rubric)).attr('href', data['rubric']['href']).text('').text(data['rubric']['value']);    
    var edit = $('td[name="edit"]', $(line));
    $('a', $(edit)).attr('href', data['edit']);
    var del = $('td[name="delete"]', $(line));
    $('a', $(del)).attr('href', data['del']);
    if (!isEdit) {
        num = num*1+1;
        $('td[name="num"]', $(line)).text('').text(num);           
        var aedit = $('a', $(edit))[0];
        aedit.dataset.id = data['id'];
        var adel = $('a', $(del))[0];
        adel.dataset.id = data['id'];
    }    
    return line;
}
