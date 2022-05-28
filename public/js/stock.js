var publicURL = $('meta[name="base-url"]').attr('content')

function getPrevStock() {
    let url = publicURL + '/stocks/get-prev-stock'
    baseAjax(url, 'GET', { date: $('#date').val() },
        function (data) {
            if (data.status == 200) {
                baseSwal('success', 'Berhasil', data.message);
                // $('#contentTableStock').empty().append(data.table_stock);
                $('#date').val(data.date);
                changeDate($('#date'));
            } else {
                baseSwal('error', 'Gagal', 'Query Error');
                console.log(data);
            }
        })
}

window.modalAddStock = function modalAddStock(e, modal) {
    resetRow(modal)
    let url = publicURL + '/stocks'
    let form = modal.find('form')
    form.trigger('reset')
    form.attr('action', url)
}

$('#formAddStock').on('submit', function (e) {
    e.preventDefault()
    formAjax($(this), $('#modalAddStock'), undefined,
        function (data, status, jqxhr, form, modal) {
            baseSwal('success', 'Berhasil', data.message)
            form.get(0).reset()
            $('#contentTableStock').empty().append(data.table_stock)
            modal.find('button.btnCloseModal').click()
        }, { tab: 'add-stock' })
})

window.modalAddItem = function modalAddItem(e, modal) {
    resetRow(modal)
    console.log('test')
    let url = publicURL + '/incomes'
    let form = modal.find('form')
    form.trigger('reset')
    form.attr('action', url)
}

$('#formAddItem').on('submit', function (e) {
    e.preventDefault()
    formAjax($(this), $('#modalAddItem'), undefined,
        function (data, status, jqxhr, form, modal) {
            baseSwal('success', 'Berhasil', data.message)
            $('select.item_id').empty().append('<option value="" selected disabled>Pilih Barang</option>')
            $.each(data.items, function (index, value) {
                $('select.item_id').append('<option value="' + value.id + '">' + value.name + '</option>')
            })
            form.get(0).reset()
            modal.find('button.btnCloseModal').click()
        }, { tab: 'item' })
})

window.modalAddIncome = function modalAddIncome(e, modal) {
    resetRow(modal)
    let url = publicURL + '/incomes'
    let form = modal.find('form')
    form.trigger('reset')
    form.attr('action', url)
}

$('#formAddIncome').on('submit', function (e) {
    e.preventDefault()
    formAjax($(this), $('#modalAddIncome'), undefined,
        function (data, status, jqxhr, form, modal) {
            baseSwal('success', 'Berhasil', data.message)
            form.get(0).reset()
            $('#contentTableIncome').empty().append(data.view)
            modal.find('button.btnCloseModal').click()
        }, { tab: 'income' })
})

window.modalEditIncome = function modalEditIncome(e, modal) {
    let btn = $(e.currentTarget)
    let url = publicURL + '/incomes/' + btn.data('id')
    let form = modal.find('form')
    form.trigger('reset')
    form.attr('action', url)
    form.find('#item_id').val(btn.data('itemid') == undefined ? '' : btn.data('itemid')).trigger('change')
    form.find('#platform').val(btn.data('platform') == undefined ? '' : btn.data('platform')).trigger('change')
    form.find('#quantity').val(btn.data('quantity') == undefined ? '' : btn.data('quantity'))
}

$('#formEditIncome').on('submit', function (e) {
    e.preventDefault()
    formAjax($(this), $('#modalEditIncome'), undefined,
        function (data, status, jqxhr, form, modal) {
            baseSwal('success', 'Berhasil', data.message)
            form.get(0).reset()
            $('#contentTableIncome').empty().append(data.view)
            modal.find('button.btnCloseModal').click()
        })
})

window.modalAddItemStock = function modalAddItemStock(e, modal) {
    resetRow(modal)
    let url = publicURL + '/stocks'
    let form = modal.find('form')
    form.trigger('reset')
    form.attr('action', url)
}

$('#formAddItemStock').on('submit', function (e) {
    e.preventDefault()
    formAjax($(this), $('#modalAddItemStock'), undefined,
        function (data, status, jqxhr, form, modal) {
            baseSwal('success', 'Berhasil', data.message)
            $('select.item_stock_id').empty().append('<option value="" selected disabled>Pilih Barang</option>')
            $.each(data.item_stocks, function (index, value) {
                $('select.item_stock_id').append('<option value="' + value.id + '">' + value.name + '</option>')
            })
            form.get(0).reset()
            modal.find('button.btnCloseModal').click()
        }, { tab: 'add-item-stock' })
})

window.modalReductionStock = function modalReductionStock(e, modal) {
    let url = publicURL + '/stocks'
    let form = modal.find('form')
    form.trigger('reset')
    form.attr('action', url)
}

$('#formReductionStock').on('submit', function (e) {
    e.preventDefault()
    formAjax($(this), $('#modalReductionStock'), undefined,
        function (data, status, jqxhr, form, modal) {
            baseSwal('success', 'Berhasil', data.message)
            form.get(0).reset()
            $('#contentTableReduction').empty().append(data.table_reduction)
            modal.find('button.btnCloseModal').click()
        }, { tab: 'reduction-stock' })
})

window.modalEditStock = function modalEditStock(e, modal) {
    let btn = $(e.currentTarget)
    let url = publicURL + '/stocks/' + btn.data('id')
    let form = modal.find('form')
    form.trigger('reset')
    form.attr('action', url)
    form.find('#item_stock_id').val(btn.data('stockid') == undefined ? '' : btn.data('stockid')).trigger('change')
    form.find('#stock').val(btn.data('stock') == undefined ? '' : btn.data('stock'))
}

$('#formEditStock').on('submit', function (e) {
    e.preventDefault()
    formAjax($(this), $('#modalEditStock'), undefined,
        function (data, status, jqxhr, form, modal) {
            baseSwal('success', 'Berhasil', data.message)
            form.get(0).reset()
            $('#contentTableStock').empty().append(data.table_stock)
            modal.find('button.btnCloseModal').click()
        }, { tab: 'stock' })
})

window.modalEditReduction = function modalEditReduction(e, modal) {
    let btn = $(e.currentTarget)
    let url = publicURL + '/stocks/' + btn.data('id')
    let form = modal.find('form')
    form.trigger('reset')
    form.attr('action', url)
    form.find('#item_stock_id').val(btn.data('stockid') == undefined ? '' : btn.data('stockid')).trigger('change')
    form.find('#expense').val(btn.data('expense') == undefined ? '' : btn.data('expense'))
    form.find('#description').val(btn.data('description') == undefined ? '' : btn.data('description'))
}

$('#formEditReduction').on('submit', function (e) {
    e.preventDefault()
    formAjax($(this), $('#modalEditReduction'), undefined,
        function (data, status, jqxhr, form, modal) {
            baseSwal('success', 'Berhasil', data.message)
            form.get(0).reset()
            $('#contentTableReduction').empty().append(data.table_reduction)
            modal.find('button.btnCloseModal').click()
        }, { tab: 'reduction' })
})

window.modalDelete = function modalDelete(e, modal) {
    let btn = $(e.currentTarget)
    let url = publicURL + '/' + btn.data('site') + '/' + btn.data('id') + '?tab=' + btn.data('tab')
    let form = modal.find('form')
    form.trigger('reset')
    form.attr('action', url)
}

$('#formDelete').on('submit', function (e) {
    e.preventDefault()
    formAjax($(this), $('#modalDelete'), undefined,
        function (data, status, jqxhr, form, modal) {
            baseSwal('success', 'Berhasil', data.message)
            form.get(0).reset()
            $('#' + data.contentTable).empty().append(data.view)
            modal.find('button.btnCloseModal').click()
        })
})

function downloadStock(e) {
    let url = publicURL + '/report-stock/' + $('#date').val();
    window.open(url, '_blank');
}

function downloadIncome(e) {
    let url = publicURL + '/report-incomes/' + $('#date').val();
    window.open(url, '_blank');
}

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
    let site = $(document).find('#date').attr('site')
    if(site === 'stocks'){
        let date = new Date(input.val());
        let now = new Date();
        if (date.toLocaleDateString() >= now.toLocaleDateString()){
            $('#getPrevStock').prop('disabled', true);
            $('#getPrevStock').addClass('hidden');
        }else{
            $('#getPrevStock').removeClass('hidden');
            $('#getPrevStock').removeAttr('disabled');
        }
    }
    let url = publicURL + '/' + site + '?date=' + input.val()
    baseAjax(url, 'GET', null, function (response) {
        if (site == 'stocks') {
            $('#contentTableStock').empty().append(response.table_stock)
            $('#contentTableReduction').empty().append(response.table_reduction)
        } else if (site == 'incomes') {
            $('#contentTableIncome').empty().append(response.table_income)
        }
    })
}

function resetRow(modal) {
    let parent = modal.find('.modal-content')
    parent.find('.grid').each(function (idx) {
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
            error: function (xhr) {
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
                        $.each(json.errors, function (index, value) {
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
            success: function (data) {
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
        intervalFormAjax = setInterval(function () {
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
            beforeSerialize: function ($form, option) {
                if (typeof callBackSerialize == 'function') {
                    if (!callBackSerialize($form, option)) {
                        loaderAjax(false)
                        return false;
                    }
                }
                return true;
            },
            error: function (xhr) {
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
                        $.each(json.errors, function (index, value) {
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
            success: function (data, status, jqxhr) {
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
        $.each(index, function (index, value) {
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
        $.each(index, function (index, value) {
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