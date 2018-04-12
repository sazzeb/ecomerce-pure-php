</div><br><br>

  <div class="col-md-12 text-center">&copy; copyright 2016 E-commerce</div>


    <script >
      jQuery(window).scroll(function(){
        var vscroll=jQuery(this).scrollTop();
        jQuery('#logotext').css({
          "transform" : "translate(0px, "+vscroll/2+"px)"
        });

        var vscroll=jQuery(this).scrollTop();
        jQuery('#back-flower').css({
          "transform" : "translate("+vscroll/5+", -"+vscroll/12+"px)"
        });


        var vscroll=jQuery(this).scrollTop();
        jQuery('#for-flower').css({
          "transform" : "translate(0px, -"+vscroll/2+"px)"
        });




        var vscroll=jQuery(this).scrollTop();
        jQuery('#partial-logotext').css({
          "transform" : "translate(0px, "+vscroll/2+"px)"
        });

        var vscroll=jQuery(this).scrollTop();
        jQuery('#partial-back-flower').css({
          "transform" : "translate("+vscroll/5+", -"+vscroll/12+"px)"
        });


        var vscroll=jQuery(this).scrollTop();
        jQuery('#partial-for-flower').css({
          "transform" : "translate(0px, -"+vscroll/2+"px)"
        });
      });


      function detailsmodal(id){
        var data = {"id" : id};
        jQuery.ajax({
          url :'/tutorial/includes/detailsmodal.php',
          method : "post",
          data : data,
          success: function(data){
            jQuery('body').append(data);
            jQuery('#details-modal').modal('toggle');
          },
          error: function(){
            alert ('something went wrong! ')
          }
        });
      }

      function update_cart(mode,edit_id,edit_size) {
        var data = {"mode" : mode, "edit_id" : edit_id, "edit_size" : edit_size};
        jQuery.ajax({
          url : '/tutorial/admin/parsers/update_cart.php',
          method : "POST",
          data : data,
            success : function () {location.reload();},
            error : function(){alert("Something Went wrong");},
        });
      }

      function add_to_cart(){
        jQuery('#modal_errors').html("");
        var size = jQuery('#size').val();
        var quantity = jQuery('#quantity').val();
        var available = jQuery('#available').val();
        var error = '';
        var data = jQuery('#add_product_form').serialize();
        if(size == '' || quantity == '' || quantity == 0){
          error += '<p class="text-danger text-center">You must choose a size quantity</p>';
          jQuery('#modal_errors').html(error);
          return;
        }else if(quantity > available){
          error += '<p class="text-danger text-center">There are only '+available+' available</p>';
          jQuery('#modal_errors').html(error);
          return;
        }else{
          jQuery.ajax({
            url: '/tutorial/admin/parsers/add_cart.php',
            type:'POST',
            data: data,
            success: function(){
              location.reload();
            },
            error: function(){alert("Something went wrong, please check value and retype")},
          });
        }
      }
    </script>
</body>
</html>
