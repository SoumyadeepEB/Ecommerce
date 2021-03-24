$(document).ready(function(){
    $('#category').on('change',function(){
        var category = $(this).val();
        $.ajax({
            url: 'getsubcategory.php',
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
            url: 'changecategorystatus.php',
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
            url: 'changesubcategorystatus.php',
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
            url: 'changeproductstatus.php',
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
});