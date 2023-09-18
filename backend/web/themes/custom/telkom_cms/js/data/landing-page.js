const base_url = (location.hostname === "localhost" || location.hostname === "127.0.0.1") ? window.location.origin + "/telkom-cms/web/" : window.location.origin + "/";
const complete_url = (window.location + "/").replace(new RegExp("#", "g"), "").replace(window.location.search, '');
const ajax_url = base_url + "ajax/";

jQuery(window).on('load', function(e){
    let checkImage = jQuery('input[name=landing_logo_url]').val();

    if (checkImage) {
        jQuery('input[name=landing_logo_url]').parent().removeClass('col-md-12 px-0').addClass('col-md-8');
        jQuery('input[name=landing_logo_url]').parent().siblings().removeClass('hidden');
        jQuery('input[name=landing_logo_url]').parent().siblings().children('img').prop('src', checkImage);
    }
});

jQuery(document).on('click', '.btn-submit-landing', function(e){
    e.preventDefault();

    const type    = document.querySelector('input[name=landing_form_type]').value;
    const data_id = document.querySelector('input[name=landing_id]');
    const form    = document.querySelector(`form#${type}-landing-page-form`);
    const formData = new FormData(form);

    // all the inputed data is correctly checked
    if (checkInput(formData)) {

        //check subdomain
        let subdomain = document.querySelector('.wizard-landing #container-subdomain input');
        let messagesSubdomain = document.querySelector('.wizard-landing #container-subdomain .messages');
        if (subdomain && messagesSubdomain) {
            if (messagesSubdomain.innerHTML != 'Available') {
                // error, don't submit
                return;
            }
            else if (subdomain.value !=='' && messagesSubdomain.innerHTML==='Available') {
                // include to payload, if exist & valid subdomain
                formData.append('landing_subdomain', subdomain.value);
            }
        }

        //check domain
        let domain = document.querySelector('.wizard-landing #container-domain input');
        let messagesDomain = document.querySelector('.wizard-landing #container-domain .messages');
        if (domain && messagesDomain) {
            if (messagesDomain.innerHTML != 'Available') {
                // error, don't submit
                return;
            }
            else if (domain.value !=='' && messagesDomain.innerHTML==='Available') {
                // include to payload, if exist & valid domain
                formData.append('landing_domain', domain.value);
            }
        }

        jQuery.ajax({
            type : "POST",
            url : `${ajax_url}landing-master/${type}/` + ((type == 'edit' && data_id) ? data_id.value : ''),
            data: formData,
            dataType: "JSON",
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(data) {
                // open alert
                Swal.fire({
                    title: 'Please wait',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    text: 'currently being processed by system...',
                    showConfirmButton: false,
                    showCancelButton: false,
                    imageUrl: `${base_url}themes/custom/telkom_cms/assets/icons/loading.gif`,
                });
            },
            success: function(response) {
                // close all alert
                Swal.close();

                if (response.status === 'success') {
                    window.location = response.data.redirect;
                }
            },
            error: function (jqXHR, errorThrown, textStatus) {
                // close all alert
                Swal.close();
            }
        });
    }else{
        return;
    }
});

jQuery(document).on('click', '.btn-add-row', function(e){
    e.preventDefault();

    let total_row = (jQuery('.product-catalog-list').length || 1) + 1;
    let cloned = jQuery('.product-catalog-list').first().clone();

    // modify parent tr
    jQuery(cloned).attr('data-drupal-selector', `edit-landing-product-${total_row}`);
    // modify label properties
    jQuery(cloned).find('label').prop('for',`edit-landing-product-${total_row}-name`);
    // modify input properties
    jQuery(cloned).find('input')
        .prop('id',`edit-landing-product-${total_row}-name`)
        .prop('name',`landing_product[${total_row}][name]`)
        .val('');

    jQuery("#edit-landing-product tbody")
        .append(cloned);
});

jQuery(document).on('click', '.btn-deleterow', function(e){
    e.preventDefault();

    if (jQuery('.product-catalog-list').length > 1) {
        jQuery(this).closest('tr').remove();
    }
});

jQuery(document).on('change', '.form-file', function(event){
    let reader = new FileReader();

    reader.onload = function(){
      let output = document.getElementById('output');
      output.src = reader.result;
    };

    reader.readAsDataURL(event.target.files[0]);

    jQuery(this).parent().removeClass('col-md-12 px-0').addClass('col-md-8');
    jQuery(this).parent().siblings().removeClass('hidden');
});

jQuery(document).on('keyup', '.input-product-catalog', debounce(function(e){
    const parentElement = e.target.parentNode;
    
    if (!e.target.value) {
      jQuery(parentElement).find('.catalog-list').remove();
      return;  
    }

    // clear catalog list if any
    jQuery(parentElement).find('.catalog-list').remove();

    jQuery.ajax({
        type : "GET",
        url : ajax_url + 'product/product-catalog/search',
        data: {
            search: e.target.value,
            landing_page_type: jQuery('#edit-landing-page-type').val()
        },
        dataType: "JSON",
        success: function(response) {
            let htmlList = '';
            response.forEach( function(element, index) {
                htmlList += `
                <div class='catalog-item' data-id='${element.nid}'>
                    ${element.name} (${element.nid})
                </div>
                `;
            });

            jQuery(parentElement).append(`<div class='catalog-list'>${htmlList}</div>`)
        },
        error: function (jqXHR, errorThrown, textStatus) {
            console.log(textStatus);
        }
    });
}, 500 ));

jQuery(document).on('click', '.catalog-item', function(e){
    // console.log()
    jQuery(this).parent().siblings('input').val(jQuery(this).text().trim());
    // clear catalog list if any
    jQuery(this).parent().remove();
});

function checkInput(form_data){
    const whitelist = ['landing_form_type','landing_logo_url','landing_page_logo'];

    let isValid = true;

    // check for every input value
    for (let input of form_data.entries()) {
        // skip when current input doesnt want to be processed
        if (whitelist.some(v => input[0].includes(v))) continue;

        if(!input[1]){
            // skip (catalog is not required if this field is hidden)
            if (input[0].includes('landing_product')) {
                let catalog_container = document.querySelector('.wizard-landing .catalog-container');
                if (catalog_container && catalog_container.style.display==='none') {
                    continue;
                }
            }
            document.querySelector(`[name="${input[0]}"]`).className += ' invalid';
            isValid = false;
        }
        else{
            document.querySelector(`[name="${input[0]}"]`).classList.remove('invalid');
        }
    }

    return isValid;
}
