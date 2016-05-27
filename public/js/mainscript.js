"use strict";

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

function handleForm(event) {
    event.preventDefault();
    var finish = false;
    var target = $(event.target);    
    var url = target.attr('href').slice(7);
    var posSlesh = url.indexOf(String.fromCharCode(47));
    if (~posSlesh) {
        url = url.slice(0, posSlesh);
    }
    var formName = url;
    var ajaxform = document.forms[formName];
    var ajaxpage = $(ajaxform).parents('div')[0];
    var url = String.fromCharCode(47) + 'books' + String.fromCharCode(47) + 'ajax' + formName;
    var fields = [];
    var selectElem = [];
    //Добавляем кн. "Cancel", цепляем слушателя
    var cancel = addCancel(ajaxform);
    $(cancel).click(function(e){        
        console.log('cancel');
        $(ajaxpage).removeClass('ajaxpage').addClass('hidden');
        $('#cover').removeClass('cover').addClass('hidden');        
        cancel.remove();
        $(ajaxform).unbind('submit');
        $('input[type!="submit"]', $(ajaxform)).each(function() {
            this.value = '';
        });
        while (selectElem.length > 0) {
            var popped = selectElem.pop();
            popped.selected = false;                                                
        }
        $('option').each(function(){
            this.selected = false;
        });
        $('[name="errprompt"]', $(ajaxform)).each(function() {
            this.remove();
        });       
    });

    var formButton = $('input[type="submit"]', $(ajaxform))[0];
    formButton.disabled = true;    
    cancel.disabled = true;
    
    //Показываем ajax окно с формой
    $('#cover').removeClass('hidden').addClass('cover');
    $(ajaxpage).removeClass('hidden').addClass('ajaxpage');

    var parentTr = $(target).parents("tr")[0];
    var id = $(target)[0].dataset.id || 0;        
    if (isFinite(id)) {
        $.ajax({
            type: "POST",            
            url: url, 
            data: {id: id},
            success: function(result){
                try {
                    var data = JSON.parse(result);                    
                } catch (er) {                        
                    flashMessage(mess, 'Error JSON', '');                        
                    $(cancel).click(); 
                    return;
                }
                if (data.error && !data.formData) {
                    flashMessage(mess, data.error, '');                        
                    $(cancel).click();
                    return;
                }                                               
                var formData = data.formData;                    
                //заполняем форму полученными данными
                for (var property in formData) {                    
                    var elem = ajaxform[property];
                    var values = formData[property];
                    if (!values) {
                        break; 
                    }                                                                                                               
                    if (-1 !== property.indexOf('[]')) {
                        $(elem).children().remove();
                        if (values.length > 0) {
                            values.forEach(function(item, i, arr) {
                                var option = document.createElement('option');
                                var $_option = $(option); 
                                $_option.val(item['value']);
                                $_option.text(item['label']);
                                $_option.prop('selected', item['selected']);
                                $(elem).append($_option);
                            });
                        }                        
                    } else {                        
                        elem.value = formData[property];
                        fields.push(property);
                    }                        
                }
                //Цепляем слушателя на отправку формы
                $(ajaxform).submit(processingForm);
                cancel.disabled = false;
                formButton.disabled = false;
            },
            error: function(result) {
                $(cancel).click();
                flashMessage(mess, 'AJAX error', '');
                console.log(result.responseText);
            }
        });
    } else {
        $(cancel).click();
        flashMessage(mess, 'Error. Id not found.', '');
    }

    function processingForm(event) {
        event.stopPropagation();
        event.preventDefault();
        formButton.disabled = true;
        cancel.disabled = true;
        $('[name="errprompt"]', $(ajaxform)).each(function() {
            this.remove();
        });        
        var formData = new FormData(ajaxform);                
        //Отправляем форму
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
                    cancel.disabled = false;
                    $(cancel).click();
                    return;
                }
                if ('success' in data) {
                    if (reload) {
                        window.location.reload();
                    } else {
                        //Действие New либо Edit
                        if (~~formName.indexOf('edit')) {  //formName - из внешней области
                            switch (formName) {
                                case 'newbook':                            
                                    var newLine = editBookLine($($('#line')[0]).clone(true, true).removeAttr('id'), data.formData, false);
                                    break
                                case 'newauthor':
                                    var newLine = editAuthorLine($($('#line')[0]).clone(true, true).removeAttr('id'), data.formData, false);
                                    break
                                case 'newrubric':                    
                                    var newLine = editRubricLine($($('#line')[0]).clone(true, true).removeAttr('id'), data.formData, false);
                                    break
                            } 
                            $(newLine).removeClass('hidden');
                            $('.table').append(newLine);
                        } else {
                            switch (formName) {     //formName, parentTr - из внешней области
                                case 'editbook':
                                    editBookLine(parentTr, data.formData, true);
                                    break
                                case 'editauthor':
                                    editAuthorLine(parentTr, data.formData, true);
                                    break
                                case 'editrubric':
                                    editRubricLine(parentTr, data.formData, true);
                                    break
                            }
                        } 
                        flashMessage(mess, '', data['success']);
                        cancel.disabled = false;
                        $(cancel).click();
                        return;
                    }
                }
                if ('error' in data && !formData in data) {
                    flashMessage(mess, data.error.descr, '');
                    cancel.disabled = false;
                    $(cancel).click();
                    return;
                }                
                var formData = data.formData;                    
                //заполняем форму полученными данными
                for (var property in formData) {                    
                    var elem = ajaxform[property];
                    var values = formData[property];
                    if (!values) {
                        break; 
                    }                                                                                           
                    if (-1 !== property.indexOf('[]')) {
                        $(elem).children().remove();
                        if (values.length > 0) {
                            values.forEach(function(item, i, arr) {
                                var option = document.createElement('option');
                                var $_option = $(option); 
                                $_option.val(item['value']);
                                $_option.text(item['label']);
                                $_option.prop('selected', item['selected']);
                                $(elem).append($_option);
                            });
                        }                        
                    } else {                        
                        elem.value = values;
                        fields.push(property);
                    }                        
                }                                
                flashMessage($('[name="message"]', $(ajaxform)), data.error.descr, '');
                //Подробная информация об ошибках формы
                var errorDatails = data.error.details;                
                for (var property in errorDatails) {                    
                    errorDatails[property].forEach(function(item, i, arr) {                        
                        var selector = "[name='" + property + "']";                        
                        addError($(selector.toString(), $(ajaxform)), item);
                    });                    
                }
                cancel.disabled = false;
                formButton.disabled = false;
            },
            error: function(result){
                console.log(result.responseText);
                flashMessage(mess, 'AJAX error', '');
                cancel.disabled = false;
                $(cancel).click();
            }
        });
    }  
}

function addCancel(elem) {
    var btn = '<button id="cancel" class="btn btn-primary">Cancel</button>';
    $(elem).append(btn);
    return $('#cancel')[0];
}

function addError(targ, text) {
    var errorLine = '<dd name="errprompt">' + text + '</dd>';    
    $(targ).after(errorLine);
}

function deleteAction(e) {            
    e.preventDefault();
    var target = e.target;
    var parentTr = $(target).parents("tr")[0];           
    var id = target.dataset.id;
    var url = $(target).attr('href').slice(7);
    var posSlesh = url.indexOf(String.fromCharCode(47));
    if (~posSlesh) {
        url = url.slice(0, posSlesh);
    }
    var formName = url;    
    url = String.fromCharCode(47) + 'books' + String.fromCharCode(47) + 'ajax' +url; 
                
    $('#cover').removeClass('hidden').addClass('cover');           
    $('#ajaxdelete').removeClass('hidden').addClass('ajaxpage');            
    
    var ajaxform = document.forms[formName];            
    $('[name^="id"]', $(ajaxform)).val(id);
    var cancel = $('[name^="cancel"]', $(ajaxform))[0];
    var btnYes = $('input[name="yes"]', $(ajaxform))[0];            
    $(cancel).click(function(e) {               
        $('#ajaxdelete').removeClass('ajaxpage').addClass('hidden');
        $('#cover').removeClass('cover').addClass('hidden');
        $(cancel).unbind("click");
        $(ajaxform).unbind("submit");
        btnYes.disabled = false;
        e.stopPropagation();               
        return;
    });

    $(ajaxform).submit(function(e) { 
        cancel.disabled = true;
        btnYes.disabled = true;
        e.stopPropagation();
        e.preventDefault();
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
                    cancel.disabled = false;
                    $(cancel).click();
                    return;                                                                
                } 
                if ('error' in data) {
                    if (parentTr !== undefined &&  -1 !== data['error'].indexOf('not found')) {
                        $(parentTr).remove();
                    }
                    flashMessage(mess, data['error'], '');
                    cancel.disabled = false;
                    $(cancel).click();
                    return;
                }
                if ('success' in data) {
                    flashMessage(mess, '', data['success']);
                    if (parentTr !== undefined) {
                        $(parentTr).remove();
                    }                    
                }                               
                if (parentTr === undefined && urlRedirect !== undefined) {
                    window.location = urlRedirect;
                }
                if (reload) {
                    window.location.reload();
                }
                cancel.disabled = false;
                btnYes.disabled = false;
                $(cancel).click();
            },
            error: function(result) {
                console.log(result.responseText);                            
                flashMessage(mess, 'AJAX error', '');
                cancel.disabled = false;
                $(cancel).click();
            }                    
        });        
    });           
}



function editBookLine(target, data, isEdit) {
    var num = 0;
    $('td[name="num"]').each(function() {
        num = ($(this).text() > num) ? $(this).text() : num;
    });
    var title = $('td[name="title"]', $(target))[0];      
    $('a', $(title))[0].dataset.id = data['id'];
    $('a', $(title)).attr('href', data['title']['href']).text('').text(data['title']['title']);
    /*var author = $('td[name="author"]', $(target));    
    $('a', $(author)).attr('href', data['author']['href']).text('').text(data['author']['value']);*/
    var dataAuthor = data['author'];
    $('td[name="author"]', $(target)).text('');
    for (var property in dataAuthor) {
        var a = document.createElement('a');
        $(a).attr('href', dataAuthor[property]['href']).text('').text(dataAuthor[property]['value']);
        $('td[name="author"]', $(target)).append(a);
    }
    var dataRubric = data['rubric'];
    $('td[name="rubric"]', $(target)).text('');
    for (var property in dataRubric) {
        var a = document.createElement('a');
        $(a).attr('href', dataRubric[property]['href']).text('').text(dataRubric[property]['value']);
        $('td[name="rubric"]', $(target)).append(a);
    }
    $('img', $(target)).attr('src', data['img']);
    var view = $('td[name="view"]', $(target))[0];
    
    $('a', $(view)).attr('href', data['view']['href']);
    var a = $('a', $(view))[0];
    a.dataset.id = data['id'];
    a.dataset.type = data['view']['type'];
    var edit = $('td[name="edit"]', $(target));
    $('a', $(edit)).attr('href', data['edit']);
    var del = $('td[name="delete"]', $(target));
    $('a', $(del)).attr('href', data['del']);
    if (!isEdit) {
        num = num*1+1;
        $('td[name="num"]', $(target)).text('').text(num);
        var aedit = $('a', $(edit))[0];
        aedit.dataset.id = data['id'];
        var adel = $('a', $(del))[0];
        adel.dataset.id = data['id'];
    }    
    return target;
}

function editAuthorLine(target, data, isEdit) {
    var num = 0;
    $('td[name="num"]').each(function() {
        num = ($(this).text() > num) ? $(this).text() : num;
    });        
    var author = $('td[name="author"]', $(target));    
    $('a', $(author)).attr('href', data['author']['href']).text('').text(data['author']['value']);
    $('td[name="name"]', $(target)).text('').text(data['name']);
    var edit = $('td[name="edit"]', $(target));
    $('a', $(edit)).attr('href', data['edit']);
    var del = $('td[name="delete"]', $(target));
    $('a', $(del)).attr('href', data['del']);
    if (!isEdit) {
        num = num*1+1;
        $('td[name="num"]', $(target)).text('').text(num);           
        var aedit = $('a', $(edit))[0];
        aedit.dataset.id = data['id'];
        var adel = $('a', $(del))[0];
        adel.dataset.id = data['id'];
    }    
    return target;
}

function editRubricLine(target, data, isEdit) {
    var num = 0;
    $('td[name="num"]').each(function() {
        num = ($(this).text() > num) ? $(this).text() : num;
    });        
    var rubric = $('td[name="rubric"]', $(target));    
    $('a', $(rubric)).attr('href', data['rubric']['href']).text('').text(data['rubric']['value']);    
    var edit = $('td[name="edit"]', $(target));
    $('a', $(edit)).attr('href', data['edit']);
    var del = $('td[name="delete"]', $(target));
    $('a', $(del)).attr('href', data['del']);
    if (!isEdit) {
        num = num*1+1;
        $('td[name="num"]', $(target)).text('').text(num);           
        var aedit = $('a', $(edit))[0];
        aedit.dataset.id = data['id'];
        var adel = $('a', $(del))[0];
        adel.dataset.id = data['id'];
    }    
    return target;
}
