
$(document).ready(function(){
    $('#resumeform-phone').inputmask("+7 999 999 99 99");

    $('input[name="ResumeForm[experience]"]').change(function(){
        var check = $(this).attr('id').slice(-1);
        addExp(check);
    });

    $('#add').on('click', function () {
        addExp(1);
    });

    $('body').on('click', '.delete', function () {
        $(this).closest('.exp').remove();
        if ($('#exp').children('div').length==0) {
            $('#add').removeClass('is_visible');
            $('#exp0').prop('checked', true);
        }
    });

    $('input[name="ResumeForm[foto]"]').change(function(){
        var formData = new FormData($(this).closest('#new')[0]);
        $.ajax({
            type: "POST",
            url: 'http://yii2/site/change',
            data:  formData,
            processData: false,
            contentType: false,
            success: function(res){
                console.log(res);
                $('#profilePhoto').attr('src', '/images/changed/'+res);
                $('input[name="ResumeForm[photo]"]').val(res);
            }
        });
    });

    $('body').on('change', '.now', function() {
        now($(this).attr('id').slice(3, ))
    });

    $('body').on('focusout', '.require', function() {
        var p = $(this).parent().children('span');
        var f = $(this).closest('.form-group');
        var valid = true;
        var err = '';
        if ($(this).val()=='') {
            valid = false;
            err = 'Поле обязательно';
        } else {
            d = new Date();
            if ($(this).attr('placeholder')=='2006') {
                if ((parseInt($(this).val())<1899)||(parseInt($(this).val())>d.getFullYear())) {
                    valid = false;
                    err = 'Значения от 1900 до текущего года';
                } else {
                    if ($(this).attr('id').slice(-7)=='endyear') {
                        var id = $(this).attr('id').slice(-9, -8);
                        var start = $('#workform-'+id+'-startyear');
                        if (($(this).val()<=start.val())||(start.val()=='')) {
                            stM = $('#workform-'+id+'-startmonth').val();
                            enM = $('#workform-'+id+'-endmonth').val();
                            if (stM>=enM) {
                                valid = false;
                                err = 'Дата окончания должна быть больше начальной';
                            }
                        }
                    } else {
                        var bd = $('#resumeform-birtday');
                        bd = bd.val().slice(-4);
                        if (($(this).val()<=bd)||(bd=='')) {
                            valid = false;
                            err = 'Дата начала работы должна быть больше дня рождения';
                        }
                    }
                }
            } else {
                valid = $(this).val().match(/^[a-zA-ZА-Яа-яЁё\s\-\&]*$/);
                err = 'В поле допустимы латинские и русские буквы, "-", "&"';
            }
        }
        if (valid) {
            f.removeClass('has-error');
            $(this).attr('aria-invalid', false);
            p.text('');
            p.removeClass('has-error help-block');
        } else {
            f.addClass('has-error');
            $(this).attr('aria-invalid', true);
            p.text(err);
            p.addClass('has-error help-block');
        }
    });

    $('#new').on('submit', function() {
        if ($('.require').length>0) {
            var thisInvalid = false;
            $.each($('.require'), function() {
                $(this).focusout();
                if ($(this).attr('aria-invalid')=='true') thisInvalid = true;
            });
            if (thisInvalid) return false;
        };
    });

    $('.delete-resume').on('click', function() {
        var resumeBlock = $(this).closest('.vakancy-page-block');
        $.ajax({
            type: "POST",
            url: "http://yii2/site/delete",
            data: {
                'id' : resumeBlock.attr('id').slice(6, ),
            },
        });
        resumeBlock.remove();
    });

    $('body').on('click','.search', function (e) {
        e.preventDefault();
        searchQuery();
        return false;
    });

    $('body').on('click', '.page, .sortLink', function(e) {
        e.preventDefault();
        searchQuery($(this).attr('href'));
        return false;
    });
    
    $('#searchAll').on('click', function () {
        window.location.reload()
    });

    $('#men').on('click', function() {
        $('#men').addClass('active');
        $('#women').removeClass('active');
        $('#all').removeClass('active');
        $('input[name="Search[sex]"]').val('1');
        searchQuery();
    });

    $('#women').on('click', function() {
        $('#men').removeClass('active');
        $('#women').addClass('active');
        $('#all').removeClass('active');
        $('input[name="Search[sex]"]').val('2');
        searchQuery();
    });

    $('#all').on('click', function() {
        $('#men').removeClass('active');
        $('#women').removeClass('active');
        $('#all').addClass('active');
        $('input[name="Search[sex]"]').val('');
        searchQuery();
    });

    $('input[name="Search[exp][]"]').on('click', function() {
        $(this).toggleClass('ch');
        var val = $(this).val();
        var input = $('input[name="Search[exp]"]');
        if ($(this).hasClass('ch')) {
            input.val(input.val()+''+val);
        } else {
            input.val(input.val().replace(val, ''));
        }
        searchQuery();
    });

    $('input[name="Search[employment][]"]').on('change', function() {
        $(this).toggleClass('ch');
        var val = $(this).val();
        var input = $('input[name="Search[employment]"]');
        if ($(this).hasClass('ch')) {
            input.val(input.val()+''+val);
        } else {
            input.val(input.val().replace(val, ''));
        }
        searchQuery();
    });

    $('input[name="Search[schedule][]"]').on('change', function() {
        $(this).toggleClass('ch');
        var val = $(this).val();
        var input = $('input[name="Search[schedule]"]');
        if ($(this).hasClass('ch')) {
            input.val(input.val()+''+val);
        } else {
            input.val(input.val().replace(val, ''));
        }
        searchQuery();
    });

    $('body').on('click', '.resume', function() {
        window.location.href = 'http://yii2/site/show-resume/?id='+$(this).attr('id');
    });

    $('input[name="Search[salary]"], input[name="Search[start]"], input[name="Search[end]"], #searchText').on('keyup', function() {
        searchQuery();
    });

});

function createGetRequestsAssoc(strGET, query) {
    strGET = strGET.replace( '/site/index?', '&');
    strGET = strGET.replace( '/site/search?', '&');
    strGET = strGET.replace( '#', '');
    var name;
    var value;
    var isName = true;
    for (var i = 0; i<strGET.length; i++) {
        if (strGET[i] === '&') {
            name = '';
            value = '';
            for (t = i+1; t<strGET.length; t++) {
                if (strGET[t]==='&') {
                    t = strGET.length;
                } else {
                    if (strGET[t]==='=') {
                        isName = false;
                    } else {
                        if (isName) {
                            name += strGET[t];
                        } else {
                            value += strGET[t];
                        }
                    }
                }
            }
            query[name] = value;
            isName = true;
        }
    }
    return query;
}

function searchQuery(strGET = '') {

    var query = [];
    
    query['sort'] = 'changed';

    if (strGET.length>0) {
        query = createGetRequestsAssoc(strGET, query);
    } else {
        if ($('#dropdownMenuLink').text().trim()=='По убыванию зарплаты') {
            query['sort'] = 'salary';
        } else {
            if ($('#dropdownMenuLink').text().trim()=='По возраствнию зарплаты') {
                query['sort'] = 'salaryDown';
            } else {
                query['sort'] = 'changed';
            }
        }
    }
    var salary = $('input[name="Search[salary]"]').val();
    if (salary.length>0) query['salary'] = salary;
    
    var start = $('input[name="Search[start]"]').val();
    if (start.length>0) query['start'] = start;
    
    var end = $('input[name="Search[end]"]').val();
    if (end.length>0) query['end'] = end;
    
    var sex = $('input[name="Search[sex]"]').val();
    if (sex.length>0) query['sex'] = sex;
    
    var spec = $('#spec').val();
    if (spec.length>0) query['spec'] = spec;
    
    var sity = $('#sity').val();
    if (sity.length>0) query['sity'] = sity;
    
    var exp = $('input[name="Search[exp]"]').val();
    if (exp.length>0) query['exp'] = exp;
    
    var employment = $('input[name="Search[employment]"]').val();
    if (employment.length>0) query['employment'] = employment;
    
    var schedule = $('input[name="Search[schedule]"]').val();
    if (schedule.length>0) query['schedule'] = schedule;
    
    var text = $('input[name="text"]').val();
    if (text.length>0) query['text'] = text;

    search(query);

}

function search(query) {

        $.ajax({
            type: "get",
            url: "http://yii2/site/search",
            data: {

                'salary' : query['salary'],
                'exp' : query['exp'],
                'start' : query['start'],
                'end' : query['end'],
                'sex' : query['sex'],
                'spec' : query['spec'],
                'sity' : query['sity'],
                'exp' : query['exp'],
                'employment' : query['employment'],
                'schedule' : query['schedule'],
                'text' : query['text'],
                'per-page' : query['per-page'],
                'page' : query['page'],
                'sort' : query['sort'],

            },
            success: function (res) {
                $('#resumes').html(res);
            },
            error: function(ts) { console.log(ts.responseText) }
        });

}

function now(id) {
        $('.end'+id).toggle('d_none');
        var d = new Date();
        var fMonth;
        switch (d.getMonth())
        {
            case 0: fMonth="Январь"; break;
            case 1: fMonth="Февраль"; break;
            case 2: fMonth="Марта"; break;
            case 3: fMonth="Апрель"; break;
            case 4: fMonth="Май"; break;
            case 5: fMonth="Июнь"; break;
            case 6: fMonth="Июль"; break;
            case 7: fMonth="Август"; break;
            case 8: fMonth="Сентябрь"; break;
            case 9: fMonth="Октябрь"; break;
            case 10: fMonth="Ноябрь"; break;
            case 11: fMonth="Декабрь"; break;
        }
        $('.end'+ id).find('.nselect__head').html('<span>'+fMonth+'</span>');
        $('select[name="WorkForm['+id+'][endMonth]"]').val(d.getMonth());
        $('input[name="WorkForm['+id+'][endYear]"]').val(d.getFullYear());

}

function addExp (add) {
    if (add == 1) { 
        child = $("#exp").children();
        id = (child.length != 0) ? parseInt(child.last().attr('id').slice(-1))+1 : 1;
        $.ajax({
        type: "POST",
        url: "http://yii2/site/work",
        data: {
            'exp' : child.length,
            'id' : id,
        },
        success: function (res) {
            $(res).appendTo('#exp') ;
            $('#add').addClass('is_visible');
            $('.nselect-1'+ id).nSelect();
            if ($('#exp').children().first().attr('id') != ('exp'+id)) {
                var b = `
                <div class="row mb24">
                    <div class="col-lg-2 col-md-3">
                    </div>
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="devide-border"></div>
                    </div>
                </div>
                `;
                $(b).prependTo('#exp'+id);
            }
        },
        error: function () {
            $('error').appendTo('#exp');
        }
    })} else {
        $('#exp').empty();
        $('#add').removeClass('is_visible');
    } 
}