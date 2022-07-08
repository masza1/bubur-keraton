var publicURL = $('meta[name="base-url"]').attr('content')

window.modalAddItem = function modalAddItem(e, modal) {
    resetRow(modal)
    let url = publicURL + '/buy_items'
    let form = modal.find('form')
    form.trigger('reset')
    form.attr('action', url)
}

$('#formAddItem').on('submit', function(e) {
    e.preventDefault()
    formAjax($(this), $('#modalAddItem'), undefined,
        function(data, status, jqxhr, form, modal) {
            baseSwal('success', 'Berhasil', data.message)
            form.get(0).reset()
            $('#contentTableItem').empty().append(data.table_item)
            modal.find('button.btnCloseModal').click()
        })
})

window.modalEditItem = function modalEditItem(e, modal) {
    let btn = $(e.currentTarget)
    let url = publicURL + '/buy_items/' + btn.data('id')
    let form = modal.find('form')
    form.trigger('reset')
    form.attr('action', url)
    form.find('#id').val(btn.data('id') == undefined ? '' : btn.data('id'))
    form.find('#item').val(btn.data('item') == undefined ? '' : btn.data('item'))
    form.find('#quantity').val(btn.data('quantity') == undefined ? '' : btn.data('quantity'))
    form.find('#price').val(btn.data('price') == undefined ? '' : btn.data('price'))
}

$('#formEditItem').on('submit', function(e) {
    e.preventDefault()
    formAjax($(this), $('#modalEditItem'), undefined,
        function(data, status, jqxhr, form, modal) {
            baseSwal('success', 'Berhasil', data.message)
            form.get(0).reset()
            $('#contentTableItem').empty().append(data.view)
            modal.find('button.btnCloseModal').click()
        })
})

window.downloadItem = function downloadItem(e, modal) {
    let btn = $(e.currentTarget)
    if (modal.find('input[name="type"]').val() == undefined) {
        modal.find('form').append('<input type="hidden" name="type" value="' + btn.attr('data-type') + '"/>')
    } else {
        modal.find('input[name="type"]').val(btn.attr('data-type'))
    }
    modal.find('form').attr('method', 'GET')
    if (btn.attr('data-type') == 'download') {
        modal.find('button[type="submit"]').text('Download')
    } else {
        modal.find('button[type="submit"]').text('View')
    }
}


$('#formDownloadBuyItem').on('submit', function(e) {
    e.preventDefault()
    let month = $(this).find('select[name="month"]').val(),
        year = $(this).find('select[name="year"]').val(),
        type = $(this).find('input[name="type"]').val()

    let url = publicURL + '/report-buy-item-month?month=' + month + '&year=' + year + '&type=' + type;
    window.open(url, '_blank');
})
window.modalDelete = function modalDelete(e, modal) {
    let btn = $(e.currentTarget)
    let url = publicURL + '/buy_items/' + btn.data('id')
    let form = modal.find('form')
    form.trigger('reset')
    form.attr('action', url)
}

$('#formDelete').on('submit', function(e) {
    e.preventDefault()
    formAjax($(this), $('#modalDelete'), undefined,
        function(data, status, jqxhr, form, modal) {
            baseSwal('success', 'Berhasil', data.message)
            form.get(0).reset()
            $('#contentTableItem').empty().append(data.view)

            modal.find('button.btnCloseModal').click()
        })
})

window.downloadItem = function downloadItem(param) {
    let url = publicURL + '/report-buy-item/' + $('#date').val() + '?type=' + param;
    window.open(url, '_blank');
}

window.downloadBuyItem = function downloadBuyItem(e, modal) {
    let btn = $(e.currentTarget)
    if (modal.find('input[name="type"]').val() == undefined) {
        modal.find('form').append('<input type="hidden" name="type" value="' + btn.attr('data-type') + '"/>')
    } else {
        modal.find('input[name="type"]').val(btn.attr('data-type'))
    }
    modal.find('form').attr('method', 'GET')
    if (btn.attr('data-type') == 'download') {
        modal.find('button[type="submit"]').text('Download')
    } else {
        modal.find('button[type="submit"]').text('View')
    }
}

$('#formDownloadBuyItem').on('submit', function(e) {
    e.preventDefault()
    let month = $(this).find('select[name="month"]').val(),
        year = $(this).find('select[name="year"]').val(),
        type = $(this).find('input[name="type"]').val()

    let url = publicURL + '/report-buy-item-month?month=' + month + '&year=' + year + '&type=' + type;
    window.open(url, '_blank');
})

function btnAddRow(e) {
    let btn = $(e.target)
    let parent = btn.closest('.modal-content')
    let len = parent.find('.grid').length
    let row = parent.find('.grid').eq(len - 1)
    let clone = row.clone().find('input, textarea').val('').end()
    clone = clone.find('select').prop('selectedIndex', 0).trigger('change').end()
    parent.append(clone)
    $('<hr>').insertAfter(row)
    parent.find(':last-child() .btnDeleteRow').removeClass('hidden').addClass('flex')
}

function btnDeleteRow(e) {
    let btn = $(e.target)
    let parent = btn.closest('.modal-content')
    let len = parent.find('.grid').length
    if (len > 1) {
        btn.closest('.grid').remove()
        len = parent.find('.grid').length
        parent.find('hr').eq(len - 1).remove()
        if (len == 1) {
            parent.find('.btnDeleteRow').removeClass('flex').addClass('hidden')
        }
    }
}

function changeDate(e) {
    let input = $(document).find('#date')
    let url = publicURL + '/buy_items?date=' + input.val()
    baseAjax(url, 'GET', null, function(response) {
        $('#contentTableItem').empty().append(response.table_item)
    })
}

function resetRow(modal) {
    let parent = modal.find('.modal-content')
    parent.find('.grid').each(function(idx) {
        let len = parent.find('.grid').length
        if (len > 1) {
            parent.find('.btnDeleteRow').click()
            if (len == 1) {
                parent.find('.btnDeleteRow').removeClass('flex').addClass('hidden')
            }
        }

    })
}

function baseAjax(url, type, param, successCallback) {
    let intervalAjax
    let requestAjax
    try {
        loaderAjax(true)
        intervalAjax = setInterval(function runAjax() {
            if (requestAjax != undefined && requestAjax != null) {
                if (requestAjax.readyState != 0 && requestAjax.readyState != 4) {
                    /**
                     * The xhr object also contains a readyState which contains the state of the 
                     * request(UNSENT-0, OPENED-1, HEADERS_RECEIVED-2, LOADING-3 and DONE-4). 
                     * we can use this to check whether the previous request was completed.
                     */
                    alert('Request dibatalkan, karna suatu alasan. Coba lagi dan pastikan internet Anda stabil!')
                    requestAjax.abort()
                    clearInterval(intervalAjax)
                    loaderAjax(false)
                }
                if (requestAjax.readyState == 4) {
                    clearInterval(intervalAjax)
                }
            }
        }, 50 * 1000)

        requestAjax = $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url,
            type: type,
            data: param,
            error: function(xhr) {
                loaderAjax(false)
                if (intervalAjax != undefined && intervalAjax != null) {
                    clearInterval(intervalAjax)
                }

                if (xhr.status == 500) {
                    baseSwal('error', 'Gagal', 'Query error', undefined);
                } else if (xhr.status == 422) {
                    let json = $.parseJSON(xhr.responseText)
                    let message = ''
                    if (json.errors != null) {
                        message = '<ul class="text-left">'
                        $.each(json.errors, function(index, value) {
                            message += '<li>' + value + '</li>'
                        })
                        message += '</ul>'
                    } else {
                        message = json.message;
                    }
                    baseSwal('error', 'Gagal', message, undefined);
                } else if (xhr.status == 404) {
                    baseSwal('error', 'Gagal', 'Halaman tidak ditemukan', undefined);
                } else if (xhr.status == 400) {
                    baseSwal('error', 'Gagal', xhr.responseText, undefined);
                } else if (xhr.status == 501) {
                    baseSwal('error', 'Gagal', $.parseJSON(xhr.responseText).message, undefined);
                }
            },
            success: function(data) {
                loaderAjax(false)
                if (intervalAjax != undefined && intervalAjax != null) {
                    clearInterval(intervalAjax)
                }
                if (data.status == 500) {
                    console.log(data);
                } else {
                    if (typeof successCallback == 'function') {
                        successCallback(data);
                    }
                }
            }
        })
    } catch (error) {
        alert('Terjadi kesalahan saat menjalankan fitur ini, mohon coba lagi')
            // console.log(error)
        loaderAjax(false)
        if (intervalAjax != undefined && intervalAjax != null) {
            clearInterval(intervalAjax);
        }
        if (requestAjax != undefined && requestAjax != null) {
            requestAjax.abort()
        }
    }
}

function formAjax(form, modal = undefined, callBackSerialize, callbackSuccess, param = null) {
    let requestFormAjax
    let xhr
    try {
        loaderAjax(true)
        intervalFormAjax = setInterval(function() {
            xhr = requestFormAjax.data('jqxhr')

            if (xhr != undefined && xhr != null) {
                if (xhr.readyState != 0 && xhr.readyState != 4) {
                    /**
                     * The xhr object also contains a readyState which contains the state of the 
                     * request(UNSENT-0, OPENED-1, HEADERS_RECEIVED-2, LOADING-3 and DONE-4). 
                     * we can use this to check whether the previous request was completed.
                     */
                    alert('Request dibatalkan, karna suatu alasan. Coba lagi dan pastikan internet Anda stabil!')
                    xhr.abort()
                    clearInterval(intervalFormAjax)
                    loaderAjax(false)
                }
                if (xhr.readyState == 4) {
                    clearInterval(intervalFormAjax)
                }
            }
        }, 50 * 1000)
        requestFormAjax = form.ajaxSubmit({
            data: param,
            beforeSerialize: function($form, option) {
                if (typeof callBackSerialize == 'function') {
                    if (!callBackSerialize($form, option)) {
                        loaderAjax(false)
                        return false;
                    }
                }
                return true;
            },
            error: function(xhr) {
                loaderAjax(false)
                if (intervalFormAjax != undefined && intervalFormAjax != null) {
                    clearInterval(intervalFormAjax)
                }
                if (xhr.status == 500) {
                    baseSwal('error', 'Gagal', 'Query error', undefined);
                } else if (xhr.status == 422) {
                    let json = $.parseJSON(xhr.responseText)
                    let message = ''
                    if (json.errors != null) {
                        message = '<ul class="text-left">'
                        $.each(json.errors, function(index, value) {
                            message += '<li>' + value + '</li>'
                        })
                        message += '</ul>'
                    } else {
                        message = json.message;
                    }
                    baseSwal('error', 'Gagal', message, undefined);
                } else if (xhr.status == 404) {
                    baseSwal('error', 'Gagal', 'Halaman tidak ditemukan', undefined);
                } else if (xhr.status == 400) {
                    baseSwal('error', 'Gagal', xhr.responseText, undefined);
                } else if (xhr.status == 501) {
                    baseSwal('error', 'Gagal', $.parseJSON(xhr.responseText).message, undefined);
                }
            },
            success: function(data, status, jqxhr) {
                loaderAjax(false)
                if (intervalFormAjax != undefined && intervalFormAjax != null) {
                    clearInterval(intervalFormAjax)
                }
                if (data.status == 500) {
                    console.log(data);
                } else {
                    if (typeof callbackSuccess == 'function') {
                        if (modal == undefined) {
                            callbackSuccess(data, status, jqxhr, form)
                        } else {
                            callbackSuccess(data, status, jqxhr, form, modal)
                        }
                    }
                }
            }
        })
    } catch (error) {
        alert('Terjadi kesalahan saat menjalankan fitur ini, mohon coba lagi')
            // console.log(error)
        loaderAjax(false)
        if (intervalFormAjax != undefined && intervalFormAjax != null) {
            clearInterval(intervalFormAjax);
        }
        if (xhr != undefined && xhr != null) {
            xhr.abort()
        }
    }
}

function loaderAjax(status) {
    if (status) {
        $(document).find('#loader').addClass('opacity-80')
        $(document).find('#loader').removeClass('hidden').addClass('flex')
    } else {
        $(document).find('#loader').removeClass('opacity-80')
        $(document).find('#loader').removeClass('flex').addClass('hidden')
    }
}

function fillForm(parent = undefined, index = [], container = undefined, content = undefined) {
    if (parent != undefined) {
        $.each(index, function(index, value) {
            if (value.type == 'input') { /* [{type:'input',data:value,content:element}] */
                parent.find(value.content).val(value.data)
            } else if (value.type == 'select' && value.timer == undefined) { /* [{type:'select',data:value,content:element}] */
                parent.find(value.content).val(value.data).trigger('change')
            } else if (value.type == 'select' && value.timer != undefined) { /* [{type:'select',data:value,content:element,stop:true,timer:true}] */
                loaderAjax(true)
                let timer = setTimeout(function run() {
                    if (parent.find(value.content + ' option').length > 1) {
                        parent.find(value.content).val(value.data).trigger('change')
                        clearTimeout(timer)
                        loaderAjax(false)
                    } else {
                        timer = setTimeout(run, 500)
                    }
                }, 500)
            } else if (value.type == 'textarea' && value.wysihtml5 == undefined) { /* [{type:'textarea',data:value,content:element}] */
                parent.find(value.content).text(value.data)
            } else if (value.type == 'textarea' && value.wysihtml5 != undefined) { /* [{type:'textarea',data:value,content:element,wysihtml5:true}] */
                parent.find(value.content).data('wysihtml5').editor.setValue(value.data)
            } else if (value.type == 'checkbox') { /* [{type:'checkbox',data:value,content:element}] */
                if (value.data) {
                    parent.find(value.content).prop('checked', true).trigger('change')
                } else {
                    parent.find(value.content).removeAttr('checked')
                }
            } else if (value.type == 'text') {
                parent.find(value.content).val(value.data)
            }
        })
    } else {
        loaderAjax(false)
        console.log('undefined parent');
    }
}

function resetForm(parent, index = []) {
    if (parent != undefined) {
        $.each(index, function(index, value) {
            if (value.type == 'select' && value.append == undefined && !value.isRemove) {
                parent.find(value.content).prop('selectedIndex', 0).trigger('change')
            } else if (value.type == 'select' && value.append != undefined) {
                parent.find(value.content).empty().append(value.append).trigger('change')
            } else if (value.type == 'select' && value.isRemove) {
                let select = parent.find(value.content)
                select.parent(value.group).find('.select2-container--default').remove()
            } else if (value.type == 'input') {
                parent.find(value.content).val(value.data)
            }
        })
    } else {
        console.log('undefined parent')
    }
}

function baseSwal(type, title, message = null, timer = undefined) {
    Swal.fire({
        title: title,
        html: message,
        icon: type,
        timer: timer == undefined ? null : timer,
        showConfirmButton: timer == undefined ? true : false,
        timerProgressBar: timer == undefined ? false : true,
    })
}