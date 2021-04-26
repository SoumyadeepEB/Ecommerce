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
                    if(arr.id == 0 || arr.id == 5){
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
            var header = []
            $(document).find('#'+id+' thead tr').each(function(){
                $(this).find('th').each(function(){
                    header.push($(this).text())
                });
            });
            return header
        }

        function getData(id){
            var summarydata = []
            $(document).find('#'+id).each(function(){
                $(this).find('td').each(function(){
                    summarydata.push(parseInt($(this).text().split(' ')[1].replace(',','')))
                });
            });
            return summarydata
        }

        function getWeekOfMonth(date) {
            const startWeekDayIndex = 1;
            const firstDate = new Date(date.getFullYear(), date.getMonth(), 1);
            const firstDay = firstDate.getDay();
          
            let weekNumber = Math.ceil((date.getDate() + firstDay) / 7);
            if (startWeekDayIndex === 1){
              if(date.getDay() === 0 && date.getDate() > 1){
                weekNumber -= 1;
              }
              if(firstDate.getDate() === 1 && firstDay === 0 && date.getDate() > 1){
                weekNumber += 1;
              }
            }
            return weekNumber;
        }

        var header = getHeader('weekly-report')
        key = date.getDay() == 0 ? 6 : date.getDay()-1
        var day = header[key]
        for(i=0; i<header.length; i++){
            if(header[i] == day){
                target_id = day.toLowerCase()
                $('#'+target_id).css('background','lightgreen')
                break
            }
        }

        var header = getHeader('monthly-report')
        var num = getWeekOfMonth(date)
        var week = 'Week'+num
        for(i=0; i<header.length; i++){
            if(header[i] == week){
                target_id = week.toLowerCase()
                $('#'+target_id).css('background','lightgreen')
                break
            }
        }

        var header = getHeader('yearly-report')
        var month = date.toLocaleString('default', {month: 'short'})
        for(i=0; i<header.length; i++){
            if(header[i] == month){
                target_id = month.toLowerCase()
                $('#'+target_id).css('background','lightgreen')
                break
            }
        }
    }

    $('#weekly-xls').on('click',function(){
        var header = getHeader('weekly-report')
        var data = getData('weekly-data')
        var total = parseInt($('#weekly-total strong span').text().split(' ')[1].replace(',',''))
        var avg = parseFloat($('#weekly-avg strong span').text().split(' ')[1].replace(',',''))

        if(header != null && data != null && total != null && avg != null){
            $.ajax({
                url: 'ajax/exportxls.php?filename=weekly_summaryreport.xlsx',
                type: 'POST',
                datatype: 'json',
                data: {'title':'Weekly Summary Report','header':header,'data':data,'total':total,'avg':avg},
                success: function(response){
                    window.location.href = 'assets/xlsx/' + response
                    console.log('download success')
                },
                error: function(){
                    console.log('internal server error')
                }
            })
        }
    });

    $('#monthly-xls').on('click',function(){
        var header = getHeader('monthly-report')
        var data = getData('monthly-data')
        var total = parseInt($('#monthly-total strong span').text().split(' ')[1].replace(',',''))
        var avg = parseFloat($('#monthly-avg strong span').text().split(' ')[1].replace(',',''))
        if(header != null && data != null && total != null && avg != null){
            $.ajax({
                url: 'ajax/exportxls.php?filename=monthly_summaryreport.xlsx',
                type: 'POST',
                datatype: 'json',
                data: {'title':'Monthly Summary Report','header':header,'data':data,'total':total,'avg':avg},
                success: function(response){
                    window.location.href = 'assets/xlsx/' + response
                    console.log('download success')
                },
                error: function(){
                    console.log('internal server error')
                }
            })
        }
    });

    $('#yearly-xls').on('click',function(){
        var header = getHeader('yearly-report')
        var data = getData('yearly-data')
        var total = parseInt($('#yearly-total strong span').text().split(' ')[1].replace(',',''))
        var avg = parseFloat($('#yearly-avg strong span').text().split(' ')[1].replace(',',''))
        if(header != null && data != null && total != null && avg != null){
            $.ajax({
                url: 'ajax/exportxls.php?filename=yearly_summaryreport.xlsx',
                type: 'POST',
                datatype: 'json',
                data: {'title':'Yearly Summary Report','header':header,'data':data,'total':total,'avg':avg},
                success: function(response){
                    window.location.href = 'assets/xlsx/' + response
                    console.log('download success')
                },
                error: function(){
                    console.log('internal server error')
                }
            })
        }
    });

    $('.adjust-stock').on('click',function(){
        var order_id = $(this).data('id')
        var products = $(this).data('product')
        var qtys = $(this).data('qty')
        var prices = $(this).data('price')

        if(order_id != null && products != null && qtys != null && prices != null){
            $.ajax({
                url: 'ajax/adjuststocks.php',
                type: 'POST',
                datatype: 'json',
                data: {'order_id':order_id,'products':products,'qtys':qtys,'prices':prices},
                success: function(response,status){
                    $('#'+order_id).slideUp();
                    console.log(status)
                },
                error: function(){
                    console.log('internal server error')
                }
            });
        }
    });
});