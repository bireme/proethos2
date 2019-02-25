
// This file is part of the ProEthos Software. 
// 
// Copyright 2013, PAHO. All rights reserved. You can redistribute it and/or modify
// ProEthos under the terms of the ProEthos License as published by PAHO, which
// restricts commercial use of the Software. 
// 
// ProEthos is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
// PARTICULAR PURPOSE. See the ProEthos License for more details. 
// 
// You should have received a copy of the ProEthos License along with the ProEthos
// Software. If not, see
// https://github.com/bireme/proethos2/blob/master/LICENSE.txt


$(function(){
        
    // masks
    $('.mask-money').mask('00000000000000000000000000000000000.00', {reverse: true});

    // initters
    $('[data-toggle="tooltip"]').tooltip()

    /* START: protocol first step */
    $( "#radio_clinical_trial_yes" ).on( "click", function() {
        $("input[name=is_consultation]").attr('disabled', true);
    });

    $( "#radio_clinical_trial_no" ).on( "click", function() {
        $("input[name=is_consultation]").attr('disabled', false);
    });

    $( "#radio_consultation_yes" ).on( "click", function() {
        $("input[name=is_clinical_trial]").attr('disabled', true);
    });

    $( "#radio_consultation_no" ).on( "click", function() {
        $("input[name=is_clinical_trial]").attr('disabled', false);
    });

    $( "#first_step, #first_step_created_protocol" ).submit( function() {
        $("input[name=is_clinical_trial]").attr('disabled', false);
        $("input[name=is_consultation]").attr('disabled', false);

    })

    if( $("#first_step_created_protocol").length ) {
        if( $('#radio_clinical_trial_yes').is(':checked') ) {
            $("input[name=is_consultation]").attr('disabled', true);
        }

        if( $('#radio_consultation_yes').is(':checked') ) {
            $("input[name=is_clinical_trial]").attr('disabled', true);
        }
    }
    /* END: protocol first step */

});