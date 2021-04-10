$(document).ready(function(){
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
});