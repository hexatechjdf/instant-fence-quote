@php
    if (auth()->check()) {
        $loc = auth()->user();
    } else {
        $loc = find_location($id);
    }
    
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,400;0,700;1,400;1,700&family=Roboto+Slab:wght@400;700&display=swap');

    .pac-container {
        z-index: 99999999999999 !important;
    }

    :root {
        --primary-color: {{ setting('estimator_primary_color', $loc->id) ?? '#ED2846' }};
        --primary-hover-color: rgb(184, 55, 74);
    }

    body {
        overflow-x: hidden;
    }

    #svg_form_time {
        height: 15px;
        max-width: 80%;
        margin: 40px auto 20px;
        display: block;
    }

    .label-heading-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 75px;
        position: relative;
    }

    .label-heading-wrapper h3 {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
    }

    section#fence_type {
        /*height: 73vh;*/
        overflow: auto;
    }

    div#section-ekG8FObFkO {
        height: 100vh;
        overflow: hidden;
    }

    /*form#estimator_survey {*/
    /*    overflow-y: auto;*/
    /*    overflow-x: hidden;*/
    /*}*/

    .slides {
        /*overflow: auto !important;*/
        max-height: 85vh;
        padding-bottom: 15px;
    }


    * {
        scrollbar-width: auto;
        scrollbar-color: var(--primary-color) #ffffff;
    }

    /* Chrome, Edge, and Safari */
    *::-webkit-scrollbar {
        width: 16px;
    }

    *::-webkit-scrollbar-track {
        background: #ffffff;
    }

    *::-webkit-scrollbar-thumb {
        background-color: var(--primary-color);
        border-radius: 10px;
        border: 3px solid #ffffff;
    }

    .optfence_style,
    .optfence_type,
    .optfence_height {
        /* border: 1px solid black; */
        background: #ffffff;
        backdrop-filter: blur(10px);
        box-shadow: 0px 2px 10px rgb(0 0 0 / 30%);
        border-radius: 9px;
        transition: transform 0.2s ease-in-out;
        box-shadow: rgb(17 17 26 / 10%) 0px 8px 12px, rgb(17 17 26 / 10%) 0px 8px 14px, rgb(17 17 26 / 10%) 0px 8px 10px;
    }

    .optfence_height:hover {
        transform: scale(1.05);
    }

    #svg_form_time circle,
    #svg_form_time rect {
        fill: white;
    }


    .sec_error {
        border: 2px solid red;
    }

    .button {
        background: var(--primary-color);
        border-radius: 5px;
        padding: 15px 25px;

        font-weight: bold;
        color: white;
        cursor: pointer;
        box-shadow: 0px 2px 5px rgb(0, 0, 0, 0.5);
    }

    .disabled {
        display: none;
    }

    section:not(.map_slide) {
        padding: 50px;
        margin: 30px auto;
        background: #ffffff;
        backdrop-filter: blur(10px);
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.3);
        border-radius: 20px;
        transition: transform 0.2s ease-in-out;
        box-shadow: rgba(17, 17, 26, 0.1) 0px 8px 24px, rgba(17, 17, 26, 0.1) 0px 16px 56px, rgba(17, 17, 26, 0.1) 0px 24px 80px;
        max-height: 70vh;
        overflow-y: auto;
        overflow-x: hidden;
    }


    .action-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: ;
        var(--primary-color);
        padding-bottom: 10px;
    }

    @media only screen and (max-width: 767px) {
        .for_padd {
            margin-top: 20px;
        }
        #personal {
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: hidden;
            border-radius: 0px;
            height: 120vh;
            padding: 5rem 1rem;
        }
    }

    .rotate-vert-right {
        -webkit-animation: rotate-vert-right 0.5s cubic-bezier(0.645, 0.045, 0.355, 1.000) both;
        animation: rotate-vert-right 0.5s cubic-bezier(0.645, 0.045, 0.355, 1.000) both;
    }

    @-webkit-keyframes rotate-vert-right {
        0% {
            -webkit-transform: rotateY(0);
            transform: rotateY(0);
            -webkit-transform-origin: right;
            transform-origin: right;
        }

        100% {
            -webkit-transform: rotateY(-360deg);
            transform: rotateY(-360deg);
            -webkit-transform-origin: right;
            transform-origin: right;
        }
    }

    @keyframes rotate-vert-right {
        0% {
            -webkit-transform: rotateY(0);
            transform: rotateY(0);
            -webkit-transform-origin: right;
            transform-origin: right;
        }

        100% {
            -webkit-transform: rotateY(-360deg);
            transform: rotateY(-360deg);
            -webkit-transform-origin: right;
            transform-origin: right;
        }
    }

    .input-hidden {
        position: absolute;
        left: -9999px;
    }

    /*input[type=radio]:checked+label>img*/
    input[type=radio]:checked+label {
        border: 1px solid #fff;
        box-shadow: 0 0 3px 3px #090;
    }

    /* Stuff after this is only to make things more pretty */
    input[type=radio]+label>img {
        width: 100%;
        height: 200px;
        transition: 500ms all;
        cursor: pointer;
    }

    input[type=radio]+label:hover {
        border: 1px solid #fff;
        box-shadow: 0 0 3px 3px #090;
    }


    .opt-radio {
        border: 2px solid #444;
        padding-left: 0px;
        padding-right: 0px;
        background: rgb(63, 62, 62);
        margin-top: 10px;
    }

    .opt-radio label {
        width: 100%;
        height: 200px;
        cursor: pointer;
    }

    .opt-radio:hover {
        border: 1px solid black;
        box-shadow: 0 0 3px 3px #090;
    }



    .range-slider {
        margin: 0px;
    }

    div#single_gates {
        margin-bottom: 20px;
    }

    .range-slider {
        width: 100%;
    }

    .range-slider__range {
        -webkit-appearance: none;
        width: calc(100% - (73px));
        height: 10px;
        border-radius: 5px;
        background: #4c4c4c;
        outline: none;
        padding: 0;
        margin: 0;
    }

    .range-slider__range::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: var(--primary-color);
        cursor: pointer;
        -webkit-transition: background 0.15s ease-in-out;
        transition: background 0.15s ease-in-out;
    }

    .range-slider__range::-webkit-slider-thumb:hover {
        background: var(--primary-hover-color);
    }

    .range-slider__range:active::-webkit-slider-thumb {
        background: var(--primary-hover-color);
    }

    .range-slider__range::-moz-range-thumb {
        width: 20px;
        height: 20px;
        border: 0;
        border-radius: 50%;
        background: var(--primary-color);
        cursor: pointer;
        -moz-transition: background 0.15s ease-in-out;
        transition: background 0.15s ease-in-out;
    }

    .range-slider__range::-moz-range-thumb:hover {
        background: var(--primary-hover-color);
    }

    .range-slider__range:active::-moz-range-thumb {
        background: var(--primary-hover-color);
    }

    .range-slider__value {
        display: inline-block;
        position: relative;
        width: 60px;
        color: #fff;
        line-height: 20px;
        text-align: center;
        border-radius: 3px;
        background: var(--primary-color);
        padding: 5px 10px;
        margin-left: 8px;
    }

    .range-slider__value:after {
        position: absolute;
        top: 8px;
        left: -7px;
        width: 0;
        height: 0;
        border-top: 7px solid transparent;
        border-right: 7px solid var(--primary-color);
        border-bottom: 7px solid transparent;
        content: "";
    }

    ::-moz-range-track {
        background: #d7dcdf;
        border: 0;
    }

    .mustdisp {
        display: block !important;
    }
</style>
<!-- Page-Title -->
@if (isset($bread))
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active"> Fence Estimator </li>
                    </ol>
                </div>
                <h4 class="page-title"> Fence Estimator </h4>
            </div>
            <!--end page-title-box-->
        </div>
        <!--end col-->
    </div>
@endif
<!-- end page title end breadcrumb -->
<script>
    var parentWindow = window.parent;
</script>


<form action="{{ route('estimator.save', $id) }}" method="GET" id="estimator_survey" enctype="multipart/form-data">
    @csrf
    <div class="row px-md-4 py-md-4">
        <div class="col-md-10 mx-auto  slides">
            <div id="svg_wrap"></div>
            <section class="rotate-vert-right active" id="personal" key="personal" next="fence_feet">
                <p>Personal information</p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="first_name">Full Name</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i data-feather="user"></i></span>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Full name" aria-label="Full name" aria-describedby="basic-addon1"
                                    value="{{ $data->name ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="first_name">Email</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i data-feather="mail"></i></span>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Email" aria-label="Email" aria-describedby="basic-addon1"
                                    value="{{ $data->email ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="first_name">Phone</label>
                            <div class="input-group mb-3">
                                <!--<span class="input-group-text" id="basic-addon1"><i data-feather="phone"></i></span>-->
                                <input type="tel" class="form-control" id="phone" name="phone"
                                    placeholder="Phone No." aria-label="phone" aria-describedby="basic-addon1"
                                    value="{{ $data->phone ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="row ml-1">
                        <div class="col-12">
                        <div class="form-group">
                            <div class="input-group mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="optin-disclaimer" required>
                                <label class="form-check-label" for="optin-disclaimer">
                                    By submitting this form and signing up for texts, you consent to receive marketing text messages (e.g. promos) from an auto dialer.  Consent is not a condition of purchase or doing business with us.  Msg & Data Rates may apply.  Unsubscribe at any time by replying STOP or clicking unsubscribe link (where available)  WE WILL NEVER SELL YOUR INFORMATION.
                                </label>
                            </div>
                        </div>
                    </div>
                




                </div>
            </section>

            <section class="rotate-vert-right map_slide" id="fence_feet" key="feet" next="fence_type"
                style="display: none">
                <div class="row">
                    @include('admin.estimator.map')
                </div>
            </section>
            <section class="rotate-vert-right" id="fence_type" key="category" next="fence_style" style="display: none">
                <div class="row">

                </div>
            </section>
            <section class="rotate-vert-right" id="fence_style" key="fence" next="fence_height"
                style="display: none">
                <div class="row">

                </div>
            </section>
            <section class="rotate-vert-right" id="fence_height" key="height" next="gates" style="display: none">
                <div class="row">

                </div>
            </section>


            <section class="rotate-vert-right flex-column" id="gates" next="fence_estimation" style="display: none">
                <div class="row1">
                    <div class="row" id="single_gates" key="single_gates">
                        <h4>Single Gates</h4>

                        <div class="range-slider">
                            <input class="range-slider__range slider" type="range" name="single_gates"
                                id="single_gate" value="0" min="0" max="10">
                            <span class="range-slider__value single_gates">0</span>
                        </div>
                    </div>

                    <div class="row" id="double_gates" key="double_gates">
                        <h4>Double Gates</h4>

                        <div class="range-slider">
                            <input class="range-slider__range slider" type="range" id="double_gate"
                                name="double_gates" value="0" min="0" max="10">
                            <span class="range-slider__value double_gates">0</span>
                        </div>

                    </div>
                </div>
                <div class="row_gates row">

                </div>

            </section>

            <section class="rotate-vert-right" id="fence_estimation" style="display: none">
                <div class="row">
                    <div class="col-md-12 text-center font-22 font-weight-bold"> Your Estimation is : </div>
                    <div class="col-md-12 text-center font-weight-bold pb-5 font-18">
                        {{ setting('estimate_currency_symbol', $loc->id) ?? '$' }} <span class="min_estimate">0</span>
                        -
                        {{ setting('estimate_currency_symbol', $loc->id) ?? '$' }} <span class="max_estimate">0</span>
                    </div>

                    <style>
                        .d_item {
                            display: flex;
                            justify-content: space-between;
                            padding: 2px 15px;

                        }

                        .for_padd {
                            box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;
                            border-radius: 17px;
                            min-height: 25vh;
                        }

                        .fence_info .d_item {
                            padding-left: 24px;
                        }

                        @media screen and (max-width: 850px) {
                            #fence_estimation {
                                padding: 5rem 0px !important;
                                border-radius: 0px !important;
                            }
                            .survey_details * {
                                box-shadow: none !important;
                            }
                        }
                    </style>

                    <div class="col-md-12 text-left">
                        <h4 class="py-3 text-success d-none"> Survey Details : </h4>
                        <div class="survey_details">
                            <div class="row">
                                {{-- personal information --}}
                                <div class="col-md-6 text-center mx-auto">
                                    {{-- name phone email --}}
                                    <div class="for_padd px-4">
                                        <h4 class="font-18 font-weight-bold py-3"> Personal Information </h4>
                                        <div class="personal_information">
                                            <div class="row  d_item">
                                                <div class=" text-left d_item_title">
                                                    <h5 class="font-weight-bold">Name </h5>
                                                </div>
                                                <div class="text-left d_item_value">
                                                    <h5 id="u_name"></h5>
                                                </div>
                                            </div>
                                            <div class="row  d_item">
                                                <div class="text-left d_item_title">
                                                    <h5 class="font-weight-bold">Email </h5>
                                                </div>
                                                <div class="text-left d_item_value">
                                                    <h5 id="u_email"></h5>
                                                </div>
                                            </div>
                                            <div class="row  d_item">
                                                <div class=" text-left d_item_title">
                                                    <h5 class="font-weight-bold">Phone </h5>
                                                </div>
                                                <div class=" text-left d_item_value">
                                                    <h5 id="u_phone"></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 text-center mx-auto">
                                    <div class="for_padd" style="">
                                        <h4 class="font-18 font-weight-bold py-3"> Fence Details </h4>
                                        <div class="fence_info">

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!--<h4 class="page-title">Your Estimation Is : </h4>-->
                        <!--$ <span class="min_estimate"></span> --->
                        <!--$ <span class="max_estimate"></span>-->
                        <input type="hidden" value="0" name="min_estimate" class="min_estimate_vl">
                        <input type="hidden" value="0" name="max_estimate" class="max_estimate_vl">
                        <input type="hidden" value="0" name="fence_estimation" class="fence_estimation">
                        <input type="hidden" value="0" name="feet" class="feet">
                        <input type="hidden" value="" name="contact_id" class="contact_id">
                        <input type="hidden" value="" name="estimator_id" class="estimator_id">
                    </div>
            </section>

            <div class="action-buttons">
                <div class="button animate-btn estimate_button" id="prev">&larr; Previous</div>
                <div class="button animate-btn estimate_button" id="next">Next &rarr;</div>
                <input type="submit" class="button animate-btn " id="submit"
                    value="{{ setting('last_slide_button_text', $loc->id) ?? 'Contact Us For This Estimate' . '!' }}">
            </div>
        </div>
    </div>
    </div>
</form>

<script src="{{ asset('assets/js/jquery.min.js') }}"></script>

<script>
    var feet = 0;
    var location_id = '{{ $id }}';
 var selection = {
        feet: -1,
        personal: -1,
        category: -1,
        fence: -1,
        height: -1,
        location_id: location_id,
        single_gates: 0,
        double_gates: 0,
        fence_estimation: 0,
        single_price: 0,
        double_price: 0,
    };

    var whereleft = @json($whereleft);
    var base_color = "rgb(230,230,230)";
    var active_color = "{{ setting('estimator_primary_color', $loc->id) ?? '#ED2846' }}";
    var child = 1;
    @if($whereleft != null)
   
    $(document).ready(function() {
        
       

        setTimeout(function() {
            selection = JSON.parse(whereleft.last_selected);
            if('feet' in selection){
                feet = selection.feet;
            }
            
            if(whereleft.where_left=='personal'){
                $('#next').trigger('click');
                return;
            }
            $('.rotate-vert-right').hide();
            $('.rotate-vert-right').removeClass('active');
            var nxtchk = $('#' + whereleft.where_left).attr('next');
            $('#' + nxtchk).show();
            $('#' + nxtchk).addClass('active');

            let is_found = false;
            $('.rotate-vert-right').each(function() {
                if ($(this).hasClass('active')) {
                    is_found = true;
                }
                if (!is_found) {
                    if (child > 1) {
                        $('#prev').show();
                    }
                    child++;
                    fill_active(child);

                }
            });

        


        }, 500);

        
    })

    @endif
</script>
<script>
    //autofill the input from local storage
    function autoFillByLocalStorage() {
        var personal = localStorage.getItem('personal') || -1;



        if (personal) {
            personal = JSON.parse(personal);
            var i_name = $('input[name="name"]');
            var i_email = $('input[name="email"]');
            var i_phone = $('input[name="phone"]');

            if (typeof personal == 'object') {
                if (personal.name) {
                    i_name.val(personal.name);
                }
                if (personal.email) {
                    i_email.val(personal.email);
                }
                if (personal.phone) {
                    i_phone.val(personal.phone);
                }
            }
        }
    }

    // Shorthand for $( document ).ready()
    $(function() {
        autoFillByLocalStorage();
    });


    function int_slide(key, slideEvt) {
       try{
            selection[key] = slideEvt;
        $('.' + key).text(slideEvt);
        $(`[name=${key}]`).val(slideEvt);
        calculate_fence_estimate(false, false);
           
       }catch(ee){}
    }

    function checkRadios(section, check=false) {
        if(check){
            return true;
        }

        var radios = $(section).find('input[type=radio]');
        var checked = false;
        for (var i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                checked = true;
                break;
            }
        }
        return checked;
    }

    function checkRange(section, check=false) {
        // if(check){
        //     return true;
        // }
        var range = $(section).find('input[type=range]');
        var checked = false;
        for (var i = 0; i < range.length; i++) {
            if (range[i].value > 0) {
                checked = true;
                break;
            }
        }
        return checked;
    }


    function is_und(val) {
        return typeof val == 'undefined' || val == "";
    }

    function validateEmail(email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test(email);
    }

    function validatePhone(getValue=false) {

        //intl tel input validat numbr
        var iti = window.intlTelInputGlobals.getInstance(document.querySelector("#phone"));
        var valid = iti.isValidNumber();
        if(getValue){
            return iti.getNumber();
        }
        return valid;
    }

    function validateOptIn() {
        var optin = $('#optin-disclaimer').is(':checked');
        return optin;
    }

    function check_personal() {

        var name = $('input[name="name"]').val().trim();
        var email = $('input[name="email"]').val().trim();
        var phone = $('input[name="phone"]').val().trim();
        var con_id = $('.contact_id').val().trim();


        var isinvalidphone = validatePhone() == false;
        var isOptInInvalid = !validateOptIn();

        if (name == "" || email == "" || phone == "" || isinvalidphone || isOptInInvalid) {
            $('#personal').addClass('sec_error');
            if (is_und(name)) {
                toastr.error('Please enter your name');
            }
            if (is_und(email)) {
                toastr.error('Please enter your Email');
            } else {
                if (!validateEmail(email)) {
                    var tr = $('#personal').find('input[name="email"]');
                    tr.addClass('is-invalid');
                    tr.focus();
                    toastr.error('Please enter valid Email');
                } else {
                    tr = $('#personal').find('input[name="email"]');
                    tr.removeClass('is-invalid');
                }
            }

            if (is_und(phone)) {
                toastr.error('Please enter your Phone');
            }

            if (isinvalidphone) {
                var ph = $('#personal').find('input[name="phone"]');

                ph.addClass('is-invalid');
                ph.focus();
                toastr.error('Please enter valid phone');

            } else {
                ph = $('#personal').find('input[name="phone"]');
                ph.removeClass('is-invalid');
            }

            if (isOptInInvalid) {
                toastr.error('Please read and accept the opt-in disclaimer');
            }

            selection.personal = -1;
            return false;
        }
        $('#personal').removeClass('sec_error');
        selection.personal = {
            name: name,
            email: email,
            phone: validatePhone(true),
            id: selection.contact_id ?? '',
             
            estimator_id : selection.estimator_id??'',
            location: "{{ $id ?? '' }}"
        };

        //save name,email and phone in local storage
        localStorage.setItem('personal', JSON.stringify(selection.personal));
        

        var url = '{{ route('estimator.saveContact', $id) }}';
        var data = selection.personal;

        console.log('sending data to', url);
        console.log(data);

        $.ajax({
            type: "get",
            url: url,
            data: data,
            success: function(data) {
                if (data.status == 'success') {
                    selection.contact_id = data.contact_id;
                    selection.estimator_id = data.estimate_id;
                    selection.personal.contact_id = data.contact_id;
                    selection.personal.estimator_id = data.estimate_id;
                    localStorage.setItem('personal', JSON.stringify(selection.personal));
                    $('.contact_id').val(data.contact_id);
                    $('.estimator_id').val(data.estimate_id);
                    return true;
                }
            }
        });
        
        return true;
    }

    function sendSelection() {
        let estimate_id = $('.estimator_id').val();
        var url = "{{ route('estimate.saveEachStep') }}/" + estimate_id;
        var data = selection;
        $.ajax({
            type: "get",
            url: url,
            data: data,
            success: function(data) {
                if (data.status == 'success') {
                    console.log("done");
                    return true;
                }
            }
        });
        return true;
    }

    function senditrezize() {
        var height = document.body.offsetHeight + extraheight;
        parentWindow.postMessage({
            key: "heightupdated",
            value: height
        }, "*");
    }

    function resizeit(extraheight = 0) {
        setTimeout(function() {
            senditrezize();
            setTimeout(function() {
                senditrezize();
                setTimeout(function() {
                    senditrezize();

                }, 1500);
            }, 900);
        }, 500);
    }

    function fill_active(index = 1) {

        $("#svg_form_time rect:nth-of-type(n + " + index + ")").css(
            "fill",
            base_color
        );
        $("#svg_form_time circle:nth-of-type(n + " + (index + 1) + ")").css(
            "fill",
            base_color
        );
        $(`circle:nth-of-type(${index})`).css("fill", active_color);
        $(`rect:nth-of-type(${index-1})`).css("fill", active_color);
    }

    $(document).ready(function() {
        // resizeit();
        setTimeout(function(){
            render_slide('fence_type', response_data.all.categories,selection.category);
            updateFields('fence_type');
            updateFields('fence_style');

            if('address' in selection){
                $('#search_box').val(selection.address);
                
            }

            $('.single_gates').html(selection.single_gates);
            $('#single_gate').val(selection.single_gates).trigger('input');
            $('.double_gates').html(selection.double_gates);
            $('#double_gate').val(selection.double_gates).trigger('input');
            setTimeout(function(){
                if(whereleft && whereleft.where_left=='gates'){
                
                    show_details();
                
                $("#submit").removeClass("disabled");
                $('#next').addClass("disabled");
            }
            },600);
        },500);
        var length = $("section").length - 1;
        $("#prev").addClass("disabled");
        $("#submit").addClass("disabled");

        $("section").not("section:nth-of-type(1)").hide();
        $("section").not("section:nth-of-type(1)").css('transform', 'translateX(100px)');

        var svgWidth = length * 200 + 24;
        $("#svg_wrap").html(
            '<svg version="1.1" id="svg_form_time" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 ' +
            svgWidth +
            ' 24" xml:space="preserve"></svg>'
        );

        function makeSVG(tag, attrs) {
            var el = document.createElementNS("http://www.w3.org/2000/svg", tag);
            for (var k in attrs) el.setAttribute(k, attrs[k]);
            return el;
        }

        var height = 6;
        for (i = 0; i < length; i++) {
            var positionX = 12 + i * 200;
            var rect = makeSVG("rect", {
                x: positionX,
                y: 9,
                width: 200,
                height: height
            });
            document.getElementById("svg_form_time").appendChild(rect);
            // <g><rect x="12" y="9" width="200" height="6"></rect></g>'
            var circle = makeSVG("circle", {
                cx: positionX,
                cy: 12,
                r: 12,
                width: positionX,
                height: height
            });
            document.getElementById("svg_form_time").appendChild(circle);
        }

        var circle = makeSVG("circle", {
            cx: positionX + 200,
            cy: 12,
            r: 12,
            width: positionX,
            height: height
        });
        document.getElementById("svg_form_time").appendChild(circle);

        $('#svg_form_time rect').css('fill', base_color);
        $('#svg_form_time circle').css('fill', base_color);


        fill_active();

        $(".estimate_button").click(function() {
            var id = $(this).attr("id");
            var slides = $(this).closest('.slides');
            // console.log(slides);
            var slideactivesection = slides.find('section.active');
            var cid = slideactivesection.attr('id');
            selection.current_slide_id = cid;
            var cnext = slideactivesection.attr('next');
            if (id == "next") {
                try {

                    if (cid == 'personal') {
                        if (!check_personal()) {
                            $(slideactivesection).addClass('sec_error');
                            return false;
                        } else {
                            $(slideactivesection).removeClass('sec_error');
                        }
                    } else if (cid != 'fence_feet' && cid != 'gates') {
                        if (!checkRadios(slideactivesection)) {
                            var sec_name = cid;
                            sec_name = sec_name.replace('_', ' ');
                            sec_name = sec_name.replace(sec_name[0], sec_name[0].toUpperCase());
                            $(slideactivesection).addClass('sec_error');
                            toastr.error('Please select atleast one option for ' + sec_name);
                            return false;
                        } else {
                            $(slideactivesection).removeClass('sec_error');
                        }
                    } else if (cid == 'gates') {

                        // if (!checkRange(slideactivesection)) {
                        //     $(slideactivesection).addClass('sec_error');
                        //     toastr.error('Either single or double gate must have value greater then 0');
                        //     return false;
                        // }else{
                        //     $(slideactivesection).removeClass('sec_error');
                        // }
                    }
                    if (selection[slideactivesection.attr('key')] == -1) {
                        return false;
                    }

                    sendSelection();
                } catch (error) {

                }
            }
            var slideactivesectionid = slideactivesection.attr('next');
            slideactivesection.removeClass('active');
            $(this).addClass("animate-btn");
            setTimeout(() => {
                $(this).removeClass('animate-btn');
            }, 200);
            $("#svg_form_time rect").css("fill", active_color);
            $("#svg_form_time circle").css("fill", active_color);


            //   console.log(slideactivesection.attr('id'));
            if (id == "next") {

                //check_personal();
                updateFields(slideactivesection.attr('id'));

                slides.find('section#' + slideactivesectionid).addClass('active');
                $("#prev").removeClass("disabled");
                if (child >= length) {
                    $(this).addClass("disabled");
                    $('#submit').removeClass("disabled");
                }
                if (child <= length) {
                    child++;
                }
            } else if (id == "prev") {

                var prev = slideactivesection.prev();
                prev.addClass('active');
                if (prev.hasClass('map_slide')) {
                    parentWindow.postMessage("add_map", "*");
                    prev.addClass('map-contain');
                    scrollLock(true);
                }


                $("#next").removeClass("disabled");
                $('#submit').addClass("disabled");
                if (child <= 2) {
                    $(this).addClass("disabled");
                }
                if (child > 1) {
                    child--;
                }
            }
            var circle_child = child + 1;
            $("#svg_form_time rect:nth-of-type(n + " + child + ")").css(
                "fill",
                base_color
            );
            $("#svg_form_time circle:nth-of-type(n + " + circle_child + ")").css(
                "fill",
                base_color
            );
            var currentSection = $("section:nth-of-type(" + child + ")");
            currentSection.fadeIn();
            currentSection.css('transform', 'translateX(0)');
            currentSection.prevAll('section').css('transform', 'translateX(-100px)');
            currentSection.nextAll('section').css('transform', 'translateX(100px)');
            $('section').not(currentSection).hide();


            // if(cid!="fence_feet" && cnext!="fence_feet"){
            //     // resizeit();
            // }

            // if(cid=="fence_type" && cnext=="fence_type"){
            //     resizeit();
            // }


        });

    });
</script>

<script>
    $('body').off('click');
    $('body').on('click', '.label-img', function() {

        var value = $(this).val();
        var parent = $(this).closest('section');
        selection[parent.attr('key')] = $(this).attr('data-index');
        selection[parent.attr('key') + "_id"] = value;
        selection[parent.attr('key') + "_label"] = $(this).attr('data-label');
        $('#next').click();
    });

    //dont submit on enter button
    $(window).keydown(function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

    $('body').on('submit', '#estimator_survey', function(e) {
        e.preventDefault();
        $('.loading').show();
        var form = $(this);
        var url = form.attr('action');
        var data = selection;

        $.ajax({
            type: "get",
            url: url,
            data: data,
            success: function(data) {

                if (data.status == 'success') {
                    toastr.success(data.message);

                    setTimeout(() => {
                        const isInsideIframe = window.self !== window.top;
                        
                        let redirectUrl;
                        if (isInsideIframe) {
                            // If the code is running inside an iframe
                            redirectUrl = `{{ setting('thank_you_page_url', $loc->id ?? null) ?? route('estimator.thank-you', $loc->id ?? null) }}`;
                        } else {
                            // If the code is NOT running inside an iframe
                            redirectUrl = `{{ setting('thank_you_page_url') ?? route('estimator.thank-you') }}`;
                        }

                        // Debugging statements
                        console.log('Is Inside Iframe:', isInsideIframe);
                        console.log('Redirect URL:', redirectUrl);

                        if (redirectUrl) {
                            // Perform the redirect
                            if (isInsideIframe) {
                            window.parent.location.href = redirectUrl;  // Redirect parent window
                            } else {
                            window.location.href = redirectUrl;  // Redirect this window
                            }
                        } else {
                            console.error('Redirect URL is not set.');
                        }
                    }, 1000);

                }
            },
            error: function(data) {
                toastr.error("Something went wrong. Please Contact Us via phone or email.");
                console.log('ERROR:', data);
            },
            complete: function(data) {
                $('.loading').hide();
            }
        });
    });
</script>


<script>
    
   var messages = {

        fence_type: "{!! setting('error_message_fencetype', $loc->id) ?? 'An error has occurred while using the fence estimator tool. If this issue persists, please contact your fence company via phone or email to get your estimate. We apologize for the inconvenience.' !!} ",
        fence_style: "{!! setting('error_message_fencestyle', $loc->id) ?? 'An error has occurred while using the fence estimator tool. If this issue persists, please contact your fence company via phone or email to get your estimate. We apologize for the inconvenience.' !!} ",
        fence_height: "{!! setting('error_message_fenceheight', $loc->id) ?? 'An error has occurred while using the fence estimator tool. If this issue persists, please contact your fence company via phone or email to get your estimate. We apologize for the inconvenience.' !!} ",
        _gates: "{!! setting('error_message_fenceftprice', $loc->id) ?? 'An error has occurred while using the fence estimator tool. If this issue persists, please contact your fence company via phone or email to get your estimate. We apologize for the inconvenience.' !!} ",
        range_gates: "{!! setting('error_message_pricerange', $loc->id) ?? 'An error has occurred while using the fence estimator tool. If this issue persists, please contact your fence company via phone or email to get your estimate. We apologize for the inconvenience.' !!} ",
        price_gates: "{!! setting('error_message_gatetypeprice', $loc->id) ?? 'An error has occurred while using the fence estimator tool. If this issue persists, please contact your fence company via phone or email to get your estimate. We apologize for the inconvenience.' !!} "
    };

    function displaymap() {

    }
    
    var newkey = '';

    function calculate_fence_estimate(checkonly = false, hideit = true) {
        if (hideit) {
            $("#single_gates").hide();
            $("#double_gates").hide();
        }
        var fence = response_data.all.categories[selection.category].fences[selection.fence];
        var ft_available = fence.ft_available[selection.height];
        var all_prices = ft_available.prices;
        var single_price = 0;
        var double_price = 0;
        var price = 0;
        var range = 10;
        var total = 0;
        if (ft_available.price != null && !isNaN(ft_available.price)) {
            price = parseFloat(ft_available.price);

            price = price * feet; //feet mean from map
        } else {
            noRecord("_gates"); //this means show the error msg and remove survey;
            return;
        }
        if (ft_available.range != null && !isNaN(ft_available.range)) {
            range = parseFloat(ft_available.range);

        } else {
            noRecord("range_gates");
            return;
        }
        single_gate_price = getTypePrice(all_prices, "single", fence.id);
        if (single_gate_price.length == 1) {
            ft_price = single_gate_price[0].ft_price;
            if (!isNaN(ft_price)) {
                selection.single_price = ft_price;
                $("#single_gates").show();
            }
        }

        double_gate_price = getTypePrice(all_prices, "double", fence.id);
        if (double_gate_price.length == 1) {
            ft_price = double_gate_price[0].ft_price;
            if (!isNaN(ft_price)) {
                selection.double_price = ft_price;
                $("#double_gates").show();
            }
        }
        if (selection.single_price + selection.double_price == 0 && !checkonly) {
            noRecord("price_gates");
        }
        if (checkonly) {
            //$(".slider").hide();

            return;
        }
        //option-radio-image.html all_prices = prices.prices;
        if (selection.single_price > 0 && selection.single_gates > 0) {
            single_price =
                selection.single_gates * parseFloat(selection.single_price);

        }

        if (selection.double_price > 0 && selection.double_gates > 0) {
            double_price =
                selection.double_gates * parseFloat(selection.double_price);

        }

        total = price + single_price + double_price;
        if (total < {{ setting('min_fee', $loc->id) ?? 0 }} && ft_available.is_min_fee == 1) {
            total = {{ setting('min_fee', $loc->id) ?? 0 }};
        }

        var rng = parseFloat(range) * feet;

        var min_estimate = (Math.abs(total - rng)).toFixed(0);
        var max_estimate = (total + rng).toFixed(0);
        document.querySelector(".min_estimate").innerHTML = min_estimate;
        document.querySelector(".max_estimate").innerHTML = max_estimate;
        document.querySelector(".min_estimate_vl").value = min_estimate;
        document.querySelector(".max_estimate_vl").value = max_estimate;
        document.querySelector(".fence_estimation").value = total;
        selection.min_estimate = min_estimate;
        selection.max_estimate = max_estimate;
        selection.fence_estimation = total;

    }

    function getTypePrice(all_prices, type, fence_id) {
        all = all_prices.filter((x) => {
            return x.type == type && x.fence_id == fence_id && x.is_active == 1;
        });
        return all;
    }

    

    @if ($whereleft)
        selection.estimator_id = '{{ $whereleft->uuid }}';  
    @endif
    function getTypePrice(all_prices, type, fence_id) {
        all = all_prices.filter((x) => {
            return x.type == type && x.fence_id == fence_id && x.is_active == 1;
        });
        return all;
    }

    function noRecord(msg) {
        console.log(msg);
        var msg1 = msg;
        var row = '.row';
        if (msg.includes('_gates')) {
            msg1 = msg.split('_')[1];

            $('.row_gates').show();
            $('#gates .row:not(.row_gates)').hide();
            row = '.row_gates';

        } else {
            $('.row:not(.row_gates)').show();
        }
        console.log(msg, messages[msg]);

        $('#' + msg1 + ' ' + row).html(`<h3 class="error text-danger text-center">An error has occurred while using the fence estimator tool. If this issue persists, please contact your fence company via phone or email to get your estimate. We apologize for the inconvenience.</h3>`)
    }

    function render_slide(newkey, values = [],default_value = -1) {
        var renderdata = $('#' + newkey + ' .row');
        renderdata.html('');
        if (values.length == 0) {
            noRecord(newkey);
            return;
        }

        $('.row_gates').hide();

        values.forEach((t, index) => {
            var img = 'default.png';
            var name = t.name;
            var show_radio = 'input-hidden';
            var id = t.id;
            if (t.hasOwnProperty('image')) {
                img = t.image;
            } else if (t.hasOwnProperty('fence_image')) {
                img = t.fence_image;
            } else {
                show_radio = '';
                img = '';
            }
            // console.log(t);
            if (t.hasOwnProperty('fence_name')) {
                name = t.fence_name;
            }
            if (t.hasOwnProperty('ft_available') && !Array.isArray(t.ft_available)) {
                name = t.ft_available.ft_available_name;
            }
            if (img !== '') {
                var imgpath = "{{ asset('') }}/" + img;
                var img_error = this.src = "https://via.placeholder.com/200x200.png?text=" + name;
                //  
                img = '<img src="' + imgpath + '" alt="' + name +
                    '" loading="lazy" class="img-responsive form-control p-0" onerror="this.src=\'' +
                    img_error + '\'">';
                // img = '<img src="' + img + '" alt="' + name + '" class="img-responsive form-control p-0">'
            }
            var html = `
                <div class="col-md-4">
                <div class="form-group">
                                <input type="radio" name="${ newkey }" data-label='${name}' value="${id}" data-index="${index}" id="${newkey}${index}" class="${show_radio} label-img" style="display:none"  ${default_value==index ?'checked':""}/>
                                <label for="${newkey}${index}" class="w-100 opt${newkey}">
                                   ${img}
                                 <div class="label-heading-wrapper">   <h3 class="text-center" style="margin:0; padding:10px; cursor:pointer">${name}</h3> </div>
                                </label>
               </div>
            </div>
                `;
            renderdata.append(html);
        });

    }

    //bg dark on fence_feet selected
    $(document).on('change', '.fence_feet', function() {
        var feet = $(this).val();
        if (feet > 0) {
            $('.fence_feet').addClass('bg-dark');
        } else {
            $('.fence_feet').removeClass('bg-dark');
        }
    });
    
    var response_data = @json($fence);
    
   

    function updateFields(field) {
        // console.log(field);
        let default_value=-1;
        //  console.log(response_data);
        var alldata = response_data.all.categories;
        //var value = this.value;
        var images = [];
        var key = field;
        
        newkey = key;
        try {
            // console.log(key);
            if (key == 'personal') {
                scrollLock(true);
                $('#fence_feet').addClass('map-contain');
                parentWindow.postMessage("add_map", "*");
                return;
            }
            if (key == 'fence_feet') {
                scrollLock(false);
                parentWindow.postMessage("remove_map", "*");
                $('#fence_feet').removeClass('map-contain');
                selection.address= $('#search_box').val();
                sendSelection();
                return;
            }
            if (key == "fence_type") {

                //var id = response_data[].id;
                // selection.category = value;
                images = alldata[selection.category].fences;
                if(selection.fence>-1){
                    default_value=selection.fence;
                }
                newkey = 'fence_style';
            }

            if (key == "fence_style") {
                //var id = response_data[].id;
                console.log("from fence style");
                // selection.fence = value;
                // images = alldata[selection.category];//.fences[selection.fence].ft_available;
                images = alldata[selection.category].fences[selection.fence].ft_available;
                if(selection.height>-1){
                    default_value=selection.height;
                }
                console.log(images);
                newkey = "fence_height";

            }
            if (key == "fence_height") {
                // selection.height = field.value;
                newkey = "gates"
                $('.row_gates').hide();
                calculate_fence_estimate(true);
                return;
            }
            if (key == 'gates') {
                calculate_fence_estimate();
                setTimeout(function() {
                    show_details();
                }, 700);
                 
                //calculate_fence_estimate(false);
                return;
            }
            render_slide(newkey, images,default_value);
        } catch (error) {

        }

    }



    function show_details(d='') {
        if(d!=''){
            selection = whereleft.last_selected;
        }
        var my_obj = {
            'Feet': selection.feet,
            'Fence Type': selection.category_label,
            'Fence Style': selection.fence_label,
            'Height': selection.height_label,
            'No of single gates': selection.single_gates,
            'No of double gates': selection.double_gates,
        };

        my_obj = JSON.parse(JSON.stringify(my_obj));
        var keys = Object.keys(my_obj);
        var values = Object.values(my_obj);
        var renderdata = $('.fence_info');
        renderdata.html('');
        keys.forEach((t, index) => {
            var html = `
            <div class="row col-md-12 d_item">
                <div class="col-md-6 text-left d_item_title">
                        <h5 class="font-weight-bold">${t}</h5>
                    </div>
                    <div class="col-md-6 text-right d_item_value">
                        <h5>${values[index]}</h5>
                    </div>
             </div>
        `;
            renderdata.append(html);
        });

        var u_name = selection.personal.name;
        var u_email = selection.personal.email;
        var u_phone = selection.personal.phone;
        // var u_address = selection.personal.address;

        $('#u_name').html(u_name);
        $('#u_email').html(u_email);
        $('#u_phone').html(u_phone);
        // $('#u_phone').html(u_phone);

    }


    $('body').on('click', '#home_btn', function() {
        parentWindow.postMessage("remove_map", "*");
    })



    var rangeSlider = function() {
        var slider = $('.range-slider'),
            range = $('.range-slider__range'),
            value = $('.range-slider__value');

        slider.each(function() {

            value.each(function() {
                var value = $(this).prev().attr('value');
                $(this).html(value);
            });

            range.on('input', function() {
                int_slide($(this).attr('name'), this.value);
                $(this).next(value).html(this.value);
            });
        });
    };

    rangeSlider();
    //blue on the nearesr radio fence_height selected
</script>

{{-- listener --}}
<script>
    function send() {
        parentWindow.postMessage("ok", "*");
    }

    function check_map() {
        var map = $('#fence_feet');
        if (map.hasClass('map-contain')) {
            return true;
        } else {
            return false;
        }
    }

    window.addEventListener("message", (e) => {
        var data = e.data;
        // console.log(typeof data);

        if (typeof data == 'string' && check_map()) {

            //post mesage
            send();
        }
    });


    // zooms out the screen for IOS devices when input is focused, after input is deselected
    document.addEventListener('focus', function(event) {
        if (event.target.tagName === 'INPUT' || event.target.tagName === 'TEXTAREA') {
            document.querySelector('meta[name="viewport"]').setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0');
        }
    }, true);

    document.addEventListener('blur', function(event) {
        if (event.target.tagName === 'INPUT' || event.target.tagName === 'TEXTAREA') {
            document.querySelector('meta[name="viewport"]').setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=1');
        }
    }, true);
</script>

<style>
    input, select, textarea {
        font-size: 16px;
    }
</style>
