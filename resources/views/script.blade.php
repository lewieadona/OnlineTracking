
<script>
    $('#branch_div').hide();
    $('#store_drop_off_div').hide();
    $('#branch_branch_dropoff_div').hide();
    
    $('form').submit(function(){
        $(this).find(':submit').attr('disabled','disabled');
    });
    
    $(function(){
        var dtToday = new Date();
        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();

        if(month < 10)
            month = '0' + month.toString();
        if(day < 10)
            day = '0' + day.toString();
        
        var maxDate = year + '-' + month + '-' + day;
        $('#purchase_date').attr('max', maxDate);
    });

    function showCity()
    {
        var provinces = document.getElementById("provinces").value;
        $.ajax
        ({ 
            url: "{{ URL::to('/province')}}",
            type: "POST",
            data: {
                'provinces': provinces,
                _token: '{!! csrf_token() !!}'
                },
            success: function(result)
            {
                var i;
                var showData = [];

                showData[0] = "<option value='' selected disabled>Choose city/municipality here...</option>";
                for (i = 1; i < result.length; ++i) {
                    var j = i + 1;
                    showData[i] = "<option value='"+result[i].citymunCode+"'>"+result[i].citymunDesc+"</option>";
                }
                $('#selectedcities').find('option').remove();
                jQuery("#selectedcities").html(showData);               
            }
        });

        $.ajax
        ({ 
            url: "{{ URL::to('/city')}}",
            type: "POST",
            data: {
                'provinces': provinces,
                _token: '{!! csrf_token() !!}'
                },
            success: function(result)
            {
                var i;
                var showData = [];
                for (i = 0; i < result.length; ++i) {
                    var j = i + 1;
                    showData[i] = "<option value='"+result[i].id+"'>"+result[i].brgyDesc+"</option>";
                }
                $('#brgytextfield').remove();
                $('#selectedbrgy').removeAttr('hidden');
                $('#selectedbrgy').attr("required", true);
                jQuery("#selectedbrgy").html(showData);              
            }
        });
    }

    // function showBrngy()
    // {
    //     var cities = document.getElementById("selectedcities").value;
    //     $.ajax
    //     ({ 
    //         url: "{{ URL::to('/city')}}",
    //         type: "POST",
    //         data: {
    //             'cities': cities,
    //             _token: '{!! csrf_token() !!}'
    //             },
    //         success: function(result)
    //         {
    //             var i;
    //             var showData = [];
    //             for (i = 0; i < result.length; ++i) {
    //                 var j = i + 1;
    //                 showData[i] = "<option value='"+result[i].id+"'>"+result[i].brgyDesc+"</option>";
    //             }
    //             $('#brgytextfield').remove();
    //             $('#selectedbrgy').removeAttr('hidden');
    //             $('#selectedbrgy').attr("required", true);
    //             jQuery("#selectedbrgy").html(showData);   
    //         }
    //     });
    // }

    var numArray = [];
    function selectedOther()
    {
        numArray = [];
        var vals = $('#items_included').val();

        if(vals.indexOf("1") == 0){
            addinputFields = `
                <label class='mb-0'>Other Items Included</label>
                <input type="text" class="form-control" name="items_included_others" placeholder="Other Items Included" required> `

            $("#show_other_item").html(addinputFields);
        }else{
            addinputFields = ` `

            $("#show_other_item").html(addinputFields);
        }
    }

    function OtherProblemDetail() 
    {
        numArray = [];
        var vals = $('#ProblemDetail').val();
        numArray.push(vals);

        numArray.forEach(function(opd) {
            opd.forEach(myFunction);
        });
    }

    function myFunction(item, index) 
    {
        if(item == 26)
        {
            addinputField = `
                <label class='mb-0'>Other Problem Details</label>
                <input type="text" class="form-control" name="problem_details_other" placeholder="Other Problem Details" required> `

            $("#show_other_problem").html(addinputField);
        }
    }

    function showStores(){
        var channel = document.getElementById("channels").value;
        $.ajax
        ({ 
            url: "{{ URL::to('/stores')}}",
            type: "POST",
            data: {
                'stores': channel,
                _token: '{!! csrf_token() !!}'
                },
            success: function(result)
            {
                var i;
                var showData = [];

                showData[0] = "<option value='' selected disabled>Choose store here...</option>";
                for (i = 0; i < result.length; ++i) {
                    var j = i + 1;
                    showData[i+1] = "<option value='"+result[i].store_name+"'>"+result[i].store_name+"</option>";
                }

                $('#selectedStores').find('option').remove();
                jQuery("#selectedStores").html(showData); 
            }
        });

        //START OF MODE OF RETURN
        if(channel == 4){
            addOption = `<option value="" selected disabled>Choose mode of return here...</option>
                            <option value="SHIP BACK">Ship Back (Cash on Pick Up)</option>
                            <option value="STORE DROP-OFF">Store Drop-Off</option>`;
            jQuery("#mode_of_return").html(addOption); 

        }else{

            addOption = `<option value="" selected disabled>Choose mode of return here...</option>
                            <option value="STORE DROP-OFF">Store Drop-Off</option>`;
            jQuery("#mode_of_return").html(addOption);
        }
        //END OF MODE OF RETURN

        //START OF CHANGING COL
        if(channel == 6 || channel == 4)
        {
            $('#branch_div').show();
            
            var element = document.getElementById("branch_div");
            element.className = element.className.replace(/\bcol-md-6\b/g, "col-md-4");
            
            var element1 = document.getElementById("purchase_location_div");
            element1.className = element1.className.replace(/\bcol-md-6\b/g, "col-md-4");

            var element2 = document.getElementById("store_div");
            element2.className = element2.className.replace(/\bcol-md-6\b/g, "col-md-4");

        }else{
            $('#branch_div').hide();
            
            var element = document.getElementById("branch_div");
            element.className = element.className.replace(/\bcol-md-4\b/g, "col-md-6");

            var element1 = document.getElementById("purchase_location_div");
            element1.className = element1.className.replace(/\bcol-md-4\b/g, "col-md-6");

            var element2 = document.getElementById("store_div");
            element2.className = element2.className.replace(/\bcol-md-4\b/g, "col-md-6");
        }
        //END OF CHANGING COL

        //START OF BANK DETAILS
        if(channel == 6){
            jQuery("#bank_name").attr("hidden",true);
            jQuery("#bank_acc_no").attr("hidden",true);
            jQuery("#bank_acc_name").attr("hidden",true);
            jQuery("#bank_text").attr("hidden",true);
            // jQuery("#show_customer_location").attr("hidden",false);
            
        }else{
            
            jQuery("#bank_name").attr("hidden",false);
            jQuery("#bank_acc_no").attr("hidden",false);
            jQuery("#bank_acc_name").attr("hidden",false);
            jQuery("#bank_text").attr("hidden",false);
            //jQuery("#show_customer_location").attr("hidden",true);
        }
        //END OF BANK DETAILS 
    }

    function showCustomerLocation()
    {
        var store_backend = document.getElementById("selectedStores").value;
        var purchase_location = document.getElementById("channels").value; 

        $.ajax
        ({ 
            url: "{{ URL::to('/backend_stores')}}",
            type: "POST",
            data: {
                'store_backend': store_backend,
                'purchase_location': purchase_location,
                _token: '{!! csrf_token() !!}'
                },
            success: function(result)
            {
                var i;
                var showData = [];

                showData[0] = "<option value='' selected disabled>Choose branch here...</option>";
                for (i = 0; i < result.length; ++i) {
                    var j = i + 1;
                    showData[i+1] = "<option value='"+result[i].branch_id+"'>"+result[i].branch_id+"</option>";
                }
                jQuery("#branch").html(showData);  
            }
        });
    }


    function showBranch()
    {
        var drop_off_store = document.getElementById("store_drop_off").value;
        var location = document.getElementById("channels").value;
        $.ajax
        ({ 
            url: "{{ URL::to('/branch_drop_off')}}",
            type: "POST",
            data: {
                'drop_off_store': drop_off_store,
                _token: '{!! csrf_token() !!}'
                },
            success: function(result)
            {
            
                var i;
                var showData = [];

                showData[0] = "<option value='' selected disabled>Choose branch here...</option>";
                for (i = 0; i < result.length; ++i) {
                    var j = i + 1;
                    showData[i+1] = "<option value='"+result[i].branch_id+"'>"+result[i].branch_id+"</option>";
                }

                //$('#customer_location').find('option').remove();
                //jQuery("#customer_location").html(showData);  
                if(channels == "RETAIL STORE"){
                    jQuery("#branch").html(showData);  
                }
                jQuery("#branch_dropoff").html(showData); 
            }
        });
    }

    function showDropOff()
    {
        var mode = document.getElementById("mode_of_return").value;

        if(mode == "STORE DROP-OFF"){
            $('#store_drop_off_div').show();
            //$('#branch_div').show();
            $('#branch_branch_dropoff_div').show();

            var element = document.getElementById("mode_of_return_div");
            element.className = element.className.replace(/\bcol-md-6\b/g, "col-md-4");
                    
            var element1 = document.getElementById("store_drop_off_div");
            element1.className = element1.className.replace(/\bcol-md-6\b/g, "col-md-4");

            var element2 = document.getElementById("branch_branch_dropoff_div");
            element2.className = element2.className.replace(/\bcol-md-6\b/g, "col-md-4");

        }else{
            $('#store_drop_off_div').hide();
           // $('#branch_div').hide();
            $('#branch_branch_dropoff_div').hide();


            var element = document.getElementById("mode_of_return_div");
            element.className = element.className.replace(/\bcol-md-4\b/g, "col-md-6");
                    
            var element1 = document.getElementById("store_drop_off_div");
            element1.className = element1.className.replace(/\bcol-md-4\b/g, "col-md-6");

            var element2 = document.getElementById("branch_branch_dropoff_div");
            element2.className = element2.className.replace(/\bcol-md-4\b/g, "col-md-6");
        }
    }
</script>

<script>
    $(document).ready(function(){
  //Chosen
  $(".limitedNumbChosen").chosen({
  })
  .bind("chosen:maxselected", function (){
  })
  //Select2
  $(".limitedNumbSelect2").select2({
  })
});
</script>

<!-- ALL JS FILES -->
<!-- <script src="js/jquery-3.2.1.min.js"></script> -->
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<!-- ALL PLUGINS -->
<script src="js/jquery.superslides.min.js"></script>
<script src="js/bootstrap-select.js"></script>
<script src="js/inewsticker.js"></script>
<script src="js/bootsnav.js."></script>
<script src="js/images-loded.min.js"></script>
<script src="js/isotope.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/baguetteBox.min.js"></script>
<script src="js/form-validator.min.js"></script>
<script src="js/contact-form-script.js"></script>
<script src="js/custom.js"></script>

</body>
</html>