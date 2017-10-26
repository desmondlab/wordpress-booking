<link rel="stylesheet" href='<?php echo get_template_directory_uri()?>/css/jquery.dataTables.min.css'>
<link rel="stylesheet" href='<?php echo get_template_directory_uri()?>/css/bootstrap.min.css'>      
<link rel="stylesheet" href='<?php echo get_template_directory_uri()?>/css/bootstrap-theme.min.css'>

<style>
.wp-toolbar{
    background-color:#fff;
}
.alert {
  color: #000 !important;
  font-weight:900!important;
}
tr.odd {
  background: #eee none repeat scroll 0 0 !important;
}
</style>

<script type="application/ecmascript;version=6" src="<?php echo get_template_directory_uri()?>/js/moment.js"></script>
<script src="<?php echo get_template_directory_uri()?>/js/jquery.dataTables.min.js"></script>
<script type="application/ecmascript;version=6" src="<?php echo get_template_directory_uri()?>/js/bootstrap.min.js"></script>
  

<!--<script src="<?php //echo get_template_directory_uri()?>/js/bootstrap-datepicker.min.js" ></script>-->

<script>
jQuery(document).ready(function($) {
    
    if( document.getElementsByClassName( 'tbl-book-class' ).length > 0 ) {
        
        $( '.rdo-class' ).on('click', function(e){
            
            if ( $( this ).is(":checked") == true ) {
                $( "#tfoot-book-class" ).show();
            }
            
        });
    }
    
    if( document.getElementsByClassName( 'datatable' ).length > 0 ) {
        $('.datatable').DataTable( {
            "order": [[ 0, "desc" ]]
        } );
    }
    
    if( jQuery( '#brands-n-models' ).length > 0 ) {
        
        $( '#brands-n-models' ).on('change', '.sort_by', function(e){
            
            var element_id = $( this ).attr( 'id' );
            var element_array = element_id.split( '_' );
            var model_id = element_array[ 2 ];
            
            $( '#update_model_order_' + model_id ).show();
            
        });
        
        
        $( '#brands-n-models' ).on( 'click', '.update_model_order', function(e) {
            
            $( '.update_model_order' ).hide();
            
            var element_id = $( this ).attr( 'id' );
            var element_array = element_id.split( '_' );
            var model_id = element_array[ 3 ];
            
            $( '#update_status_' + model_id ).html( 'updating...' );
            
            var update_model_order_url = $( '#update_model_order_url' ).val();
            
            // handle to update selected model's order
            jQuery.ajax({
                type: 'POST',
                url: update_model_order_url,
                data: {
                    'model_id': model_id,
                    'order_value': $( '#sort_by_' + model_id ).val()
                },
                dataType: 'json',
                success:function(data) {

                    var msg = data.message;

                    $( '#update_status_' + model_id ).html( msg );

                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
            
        } )
        
    }//end if
        
    if ( document.getElementsByName('start_date').length > 0 ) {
        
       // jQuery('#start_date').datetimepicker();
        
        //show date-picker only
//        $("input[name='start_date']").datetimepicker({
//            locale: 'ru'
//        });
        
        //show time-picker only
//        $("input[name='start']").datetimepicker({
//           minView: 2, 
//           format:'dd-MM-yyyy'
//        });
        
//        $("input[name='end']").datetimepicker({
//            minView: 2,
//            format:'dd-MM-yyyy'
//        });
    }
        
    
    
} );

function del_brand(id)
{
    location.href="booking/del_brand.php?id="+id;
}

function del_model(id)
{
    location.href="booking/del_model.php?id="+id;
}
</script>

