base_url = window.location.origin + '/ecommerce/'
var date = new Date()

function liveclock(){
    $.ajax({
        url: 'ajax/liveclock.php',
        type: 'POST',
        datatype: 'json',
        success: function(response){
            $('#datetime #time').html(response)
        },
        error: function(){
            console.log('internal server error')
        }
    });
}

$(document).ready(function(){
    setInterval(liveclock,1000)

    $('#category').on('change',function(){
        var category = $(this).val();
        $.ajax({
            url: 'ajax/getsubcategory.php',
            type: 'POST',
            datatype: 'json',
            data: {'category':category},
            beforeSend: function() {
                $('#loader').show()
            },
            success: function(response){
                $('#subcategory').html(response)
            },
            error: function(){
                console.log('internal server error')
            },
            complete: function() {
                $('#loader').hide();
            }
        });
    });

    $('.category_status').on('change',function(){
        var id = $(this).data('cid');
        var status = $(this).val();
        $.ajax({
            url: 'ajax/changecategorystatus.php',
            type: 'POST',
            datatype: 'json',
            data: {'id':id,'status':status},
            beforeSend: function() {
                $('#loader').show()
            },
            success: function(response){
                console.log(response)
            },
            error: function(){
                console.log('internal server error')
            },
            complete: function() {
                $('#loader').hide();
            }
        });
    });

    $('.subcategory_status').on('change',function(){
        var id = $(this).data('sid');
        var status = $(this).val();
        $.ajax({
            url: 'ajax/changesubcategorystatus.php',
            type: 'POST',
            datatype: 'json',
            data: {'id':id,'status':status},
            beforeSend: function() {
                $('#loader').show()
            },
            success: function(response){
                console.log(response)
            },
            error: function(){
                console.log('internal server error')
            },
            complete: function() {
                $('#loader').hide();
            }
        });
    });

    $('.product_status').on('change',function(){
        var id = $(this).data('pid');
        var status = $(this).val();
        $.ajax({
            url: 'ajax/changeproductstatus.php',
            type: 'POST',
            datatype: 'json',
            data: {'id':id,'status':status},
            beforeSend: function() {
                $('#loader').show()
            },
            success: function(response){
                console.log(response)
            },
            error: function(){
                console.log('internal server error')
            },
            complete: function() {
                $('#loader').hide();
            }
        });
    });

    $('.product').on('mouseover',function(){
        var slno = $(this).data('num')
        var id = $(this).data('pid')
        $.ajax({
            url: 'ajax/getproductimage.php',
            type: 'POST',
            datatype: 'json',
            data: {'id':id},
            success: function(response){
                $('#'+slno+'-'+id).popover({
                    placement: 'top',
                    trigger: 'hover',
                    html: true,
                    content: "<div class='text-center'><img src='assets/images/products/"+response+"' width='100' height='100'></div>"
                });
            },
            error: function(){
                console.log('internal server error')
            }
        });
    });

    $('#order-action button').on('click',function(){
        var status = $(this).data('action')
        var id = $(this).data('id')
        if(confirm('Are you sure to change the order status')){
            $.ajax({
                url: 'ajax/changeorderstatus.php',
                type: 'POST',
                datatype: 'json',
                data: {'status':status,'id':id},
                success: function(response1){
                    var arr = JSON.parse(response1)
                    if(arr.id == 0){
                        $('#'+id+' td span').html(arr.status)
                        $('#'+id+' td span').attr('class','badge badge-danger')
                        $('#ecom'+id).empty()
                    }
                    else if(arr.id == 2){
                        $('#'+id+' td span').html(arr.status)
                        $('#'+id+' td span').attr('class','badge badge-primary')
                        var html = ''
                        html += '<button class="dropdown-item" data-action="3" data-id="'+id+'">Shipped</button>'
                        html += '<button class="dropdown-item" data-action="4" data-id="'+id+'">Delivered</button>'
                        html += '<button class="dropdown-item" data-action="0" data-id="'+id+'">Cancelled</button>'
                        $('#ecom'+id+' #order-action').html(html)
                    }
                    else if(arr.id == 3){
                        $('#'+id+' td span').html(arr.status)
                        $('#'+id+' td span').attr('class','badge badge-info')
                        var html = ''
                        html += '<button class="dropdown-item" data-action="4" data-id="'+id+'">Delivered</button>'
                        html += '<button class="dropdown-item" data-action="0" data-id="'+id+'">Cancelled</button>'
                        $('#ecom'+id+' #order-action').html(html)
                    }
                    else{
                        $('#'+id+' td span').html(arr.status)
                        $('#'+id+' td span').attr('class','badge badge-success')
                        $('#ecom'+id).empty()
                    }

                    if(response1 != null){
                        $.ajax({
                            url: 'ajax/setnotification.php',
                            type: 'POST',
                            datatype: 'json',
                            data: {'order_id':id,'json':response1},
                            success: function(response2){
                                console.log(response2)
                            },
                            error: function(){
                                console.log('internal server error')
                            }
                        })
                    }
                },
                error: function(){
                    console.log('internal server error')
                }
            });
        }
    });

    if(window.location.href == base_url + 'ordersummary.php'){
        var today_income = parseInt($('#today').text().split(' ')[1].replace(',',''))
        var yesterday_income = parseInt($('#yesterday').text().split(' ')[1].replace(',',''))
        if(today_income > yesterday_income){
            icon1 = "<i class='fas fa-arrow-circle-up text-success'></i>"
            icon2 = "<i class='fas fa-arrow-circle-down text-danger'></i>"
        }else if(today_income < yesterday_income){
            icon1 = "<i class='fas fa-arrow-circle-down text-danger'></i>"
            icon2 = "<i class='fas fa-arrow-circle-up text-success'></i>"
        }else{
            icon1 = icon2 = "<i class='fas fa-minus-circle text-secondary'></i>"
        }
        $('#today span').html(icon1)
        $('#yesterday span').html(icon2)

        function getHeader(id){
            header = []
            $(document).find('#'+id+' thead tr').each(function(){
                $(this).find('th').each(function(){
                    header.push($(this).text())
                });
            });
        }

        getHeader('weekly-report')
        key = date.getDay() == 0 ? 6 : date.getDay()-1
        var day = header[key]
        for(i=0; i<header.length; i++){
            if(header[i] == day){
                target_id = day.toLowerCase()
                $('#'+target_id).css('background','lightgreen')
                break
            }
        }

        getHeader('monthly-report')
        var adjustedDate = date.getDate() + date.getDay();
        var prefixes = ['0','1','2','3','4','5'];
        var num = parseInt(prefixes[0 | adjustedDate / 7]) + 1
        var week = 'Week'+num
        for(i=0; i<header.length; i++){
            if(header[i] == week){
                target_id = week.toLowerCase()
                $('#'+target_id).css('background','lightgreen')
                break
            }
        }

        getHeader('yearly-report')
        var month = date.toLocaleString('default', {month: 'short'})
        for(i=0; i<header.length; i++){
            if(header[i] == month){
                target_id = month.toLowerCase()
                $('#'+target_id).css('background','lightgreen')
                break
            }
        }
    }
});