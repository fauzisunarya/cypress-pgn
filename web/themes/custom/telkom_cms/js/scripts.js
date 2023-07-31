/**
 * Side Navigation
 */
window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sidenav-toggled');
            localStorage.setItem('sidebar-toggle', document.body.classList.contains('sidenav-toggled'));
        });
    }

});

//your javascript goes here
var currentTab = 0;
document.addEventListener("DOMContentLoaded", function (event) {
    let formLogin = document.querySelector('#login-wrapper #otp');
    if (formLogin) {
      OTPInput();
      showTab(currentTab);
    }
});

function OTPInput() {
    const inputs = document.querySelectorAll('#otp > *[id]');
    for (let i = 0; i < inputs.length; i++) {
        inputs[i].addEventListener('keydown', function(event) {
            if (event.key==="Backspace" ) {
                inputs[i].value='' ;
                if (i !==0) inputs[i - 1].focus();
            }
            else {
                if (i===inputs.length - 1 && inputs[i].value !=='' ) {
                    return true;
                } else if (event.keyCode> 47 && event.keyCode < 58) {
                    inputs[i].value=event.key;
                    if (i !==inputs.length - 1) inputs[i + 1].focus();
                    event.preventDefault();
                } else if (event.keyCode> 64 && event.keyCode < 91) {
                    inputs[i].value = String.fromCharCode(event.keyCode);
                    if (i !==inputs.length - 1) inputs[i + 1].focus();
                    event.preventDefault();
                }
            }
        });
    }
}

// Form Wizard
function showTab(n) {
    var x = document.getElementsByClassName("tab");
    for (let n = 0; n < x.length; n++) {
        x[n].style.display = "block";
        if (n == 0) {
            document.getElementById("prevBtn").style.display = "none";
        } else {
            document.getElementById("prevBtn").style.display = "inline";
        }
        if (n == (x.length - 1)) {
            document.getElementById("nextBtn").innerHTML = "Submit";
        } else {
            document.getElementById("nextBtn").innerHTML = "Next";
        }
        fixStepIndicator(n)
    }
}

function nextPrev(n) {
    var x = document.getElementsByClassName("tab");
    if (n == 1 && !validateForm()) return false;
    x[currentTab].style.display = "none";
    currentTab = currentTab + n;
    if (currentTab >= x.length) {
        document.getElementById("nextprevious").style.display = "none";
        document.getElementById("all-steps").style.display = "none";
        document.getElementById("form-title").style.display = "none";
        document.getElementById("text-message").style.display = "block";




    }
    showTab(currentTab);
}

function validateForm() {
    var x, y, i, valid = true;
    x = document.getElementsByClassName("tab");
    y = x[currentTab].getElementsByTagName("input");
    for (i = 0; i < y.length; i++) {
        if (y[i].value == "") {
            y[i].className += " invalid";
            valid = false;
        }
    }

    if (valid) {
        document.getElementsByClassName("step")[currentTab].className += " finish";
    }

    return valid;
}

function fixStepIndicator(n) {
    var i, x = document.getElementsByClassName("step");
    for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
    }

    x[n].className += " active";
}

let submenu = document.getElementsByClassName('sub-menu')

function showMenu(e) {
    for (let index = 0; index < submenu.length; index++) {
        let el = submenu[index].id
        if (e == el) {
            document.getElementById(el).style.left = '4.5rem'
        } else {
            document.getElementById(el).style.left = '-20rem'
        }
    }
}

function hideMenu(e) {
    for (let index = 0; index < submenu.length; index++) {
        let el = submenu[index].id
        if (e == el) {
            document.getElementById(el).style.left = '-20rem'
        }
    }
}

// handle position submenu
window.addEventListener('scroll', function() {
    var submenu = document.getElementsByClassName('sub-menu')
    for (let index = 0; index < submenu.length; index++) {
        if (window.pageYOffset > 100) {
            submenu[index].style.top = '0'
        } else {
            submenu[index].style.top = 'auto'
        }
    }
})

jQuery(document).on('click', '.btn-login', function(e){
    e.preventDefault();

    const formData = new FormData(jQuery('.form-login')[0]);

    jQuery.ajax({
        url: `${base_url}ajax/auth/login`,
        type: 'POST',
        data: formData,
        dataType: 'JSON',
        beforeSend: function(data) {
            // open alert
            Swal.fire({
                title: 'Please wait',
                allowOutsideClick: false,
                allowEscapeKey: false,
                text: 'currently being checked by system...',
                showConfirmButton: false,
                showCancelButton: false,
                imageUrl: `${base_url}themes/custom/telkom_cms/assets/icons/loading.gif`,
            });
        },
        success: function(response) {
            // close all alert
            Swal.close();

            if (response.code == 200) {
                if (response.data.telegram_id) {
                    // hide component
                    jQuery('.form-login').hide();
                    jQuery('.forgot-pass').removeClass('d-flex').addClass('d-none');
                    jQuery('.form-otp').removeClass('d-none').addClass('d-flex');

                    jQuery('input[name=user_id]').val(response.data.nid);
                }
                else{
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Success login with provided account!',
                        showConfirmButton: false,
                        showCancelButton: false,
                    });

                    window.location.reload();
                };

            } else {
                // show notification
                Swal.fire('Failed!', response.message, 'error').then((result) => {
                    if (result.isConfirmed) {
                        // reload capcay click when capcay is not accepted by system
                        grecaptcha.reset();
                    }
                });
            }
        },
        cache: false,
        contentType: false,
        processData: false
    });
});

jQuery(document).on('click', '.btn-validate', function(e){
    e.preventDefault();

    let otp_code = '';

    jQuery('input.rounded').each(function() {
        otp_code += jQuery(this).val();
    });

    jQuery.ajax({
        url: `${base_url}ajax/auth/otp`,
        type: 'POST',
        data: {
            user_id: jQuery('input[name=user_id]').val(),
            otp_code: otp_code
        },
        dataType: 'JSON',
        beforeSend: function(data) {
            // open alert
            Swal.fire({
                title: 'Please wait',
                allowOutsideClick: false,
                allowEscapeKey: true,
                text: 'currently being checked by system...',
                showConfirmButton: false,
                showCancelButton: false,
                imageUrl: `${base_url}themes/custom/telkom_cms/assets/icons/loading.gif`,
            });
        },
        success: function(response) {
            // close all alert
            Swal.close();

            if (response.code == 200) {

                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    showConfirmButton: false,
                    showCancelButton: false,
                });

                window.location.reload();

            } else {
                jQuery('.btn-resend').removeClass('d-none').addClass('d-inline');
                jQuery('.spn-separator').removeClass('d-none').addClass('d-flex');
                Swal.fire('Failed!', response.message, 'error');
            }
        }
    });
});

jQuery(document).on('click', '.btn-resend', function(e){
    e.preventDefault();

    jQuery('input.rounded').each(function() {
        jQuery(this).val('');
    });

    jQuery.ajax({
        url: `${base_url}ajax/auth/resendotp`,
        type: 'POST',
        data: {
            user_id: jQuery('input[name=user_id]').val()
        },
        dataType: 'JSON',
        beforeSend: function(data) {
            // open alert
            Swal.fire({
                title: 'Please wait',
                allowOutsideClick: false,
                allowEscapeKey: true,
                text: 'currently being checked by system...',
                showConfirmButton: false,
                showCancelButton: false,
                imageUrl: `${base_url}themes/custom/telkom_cms/assets/icons/loading.gif`,
            });
        },
        success: function(response) {
            // close all alert
            Swal.close();

            if (response.code == 200) {
                jQuery('.btn-resend').removeClass('d-inline').addClass('d-none');
                jQuery('.spn-separator').removeClass('d-flex').addClass('d-none');
            } else {
                Swal.fire('Failed!', response.message, 'error');
            }
        }
    });
});

// form wizard landing
var currentTabLanding = 0;
document.addEventListener("DOMContentLoaded", function (event) {
    let x = document.getElementsByClassName("tab");
    for (let i = 0; i < x.length; i++) {
      x[i].style.display = 'none';
    }

    let wizardLanding = document.querySelector('.wizard-landing');
    if (wizardLanding) {
      
      showTabLanding(currentTabLanding);
  
      let landing_page_type = null;//document.getElementById('edit-landing-page-type');
      if (landing_page_type) {
        
        let catalog_container = document.querySelector('.wizard-landing .catalog-container');
        
        landing_page_type.addEventListener('change', function(e){
          if (catalog_container) {
            for (const option of e.target.children) {
              if (option.selected) {
                if (option.innerText === 'MO') {
                  catalog_container.style.display = 'none';
                }
                else{
                  catalog_container.style.display = 'block';
                }
              }
            }
          }
        });
  
        // if type===MO, hide catalog (in edit form page), when page load 
        if (catalog_container) {
          for (const option of landing_page_type.children) {
            if (option.selected) {
              if (option.innerText === 'MO') {
                catalog_container.style.display = 'none';
              }
              else{
                catalog_container.style.display = 'block';
              }
            }
          }
        }
      }
    }
});

// Form Wizard
function showTabLanding(n) {
    var x = document.getElementsByClassName("tab");
    x[n].style.display = "block";
    if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }
    if (n == (x.length - 1)) {
        document.getElementById("nextBtn").style.display = "none";
        document.querySelector(".btn-submit-landing").style.display = "block";
    } else {
        document.getElementById("nextBtn").style.display = "block";
        document.querySelector(".btn-submit-landing").style.display = "none";
        document.getElementById("nextBtn").innerHTML = "Next";
    }
    fixStepIndicatorLanding(n)
}

function nextPrevLanding(n) {
  var x = document.getElementsByClassName("tab");
  if (n == 1 && !validateFormLanding()) return false;
  x[currentTabLanding].style.display = "none";
    currentTabLanding = currentTabLanding + n;
    if (currentTabLanding >= x.length) {
        document.getElementById("nextprevious").style.display = "none";
        document.getElementById("all-steps").style.display = "none";
        document.getElementById("text-message").style.display = "block";

    }
    showTabLanding(currentTabLanding);
}

function validateFormLanding() {
    var x, y, i, z, a, valid = true;
    x = document.getElementsByClassName("tab");
    y = x[currentTabLanding].getElementsByTagName("input");
    z = x[currentTabLanding].getElementsByTagName("textarea");

    for (i = 0; i < y.length; i++) {
        // skip
        if (y[i].name.includes('landing_product')) {
            // let catalog_container = document.querySelector('.wizard-landing .catalog-container');
            // if (catalog_container && catalog_container.style.display==='none') {
            //     continue;
            // }
        }
        else if ( y[i].id === 'input-subdomain') {
          let messages = document.querySelector('.wizard-landing #container-subdomain .messages');
          if (messages && messages.innerHTML === 'Available') {
            y[i].classList.remove('invalid');
            continue;
          }
          else {
            y[i].className += " invalid";
            valid = false;
            continue;
          }
        }
        else if ( y[i].id === 'input-domain') {
          let messages = document.querySelector('.wizard-landing #container-domain .messages');
          if (messages && messages.innerHTML === 'Available') {
            y[i].classList.remove('invalid');
            continue;
          }
          else {
            y[i].className += " invalid";
            valid = false;
            continue;
          }
        }

        if (y[i].value == "") {
            y[i].className += " invalid";
            valid = false;
        }
        else{
          y[i].classList.remove('invalid');
        }
    }
    for (a = 0; a < z.length; a++) {
      if (z[a].value == "") {
          z[a].className += " invalid";
          valid = false;
      }
      else{
        z[a].classList.remove('invalid');
      }
    }

    if (valid) {
        document.getElementsByClassName("step")[currentTabLanding].className += " finish";
    }

    return valid;
}

function fixStepIndicatorLanding(n) {
    var i, x = document.getElementsByClassName("step");
    for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
    }

    x[n].className += " active";
}

// domain custom
if (document.querySelector('.wizard-landing #container-domain input')) {
  let messages = document.querySelector('.wizard-landing #container-domain .messages');
  let input = document.querySelector('.wizard-landing #container-domain input');
  let prevValue = input.value;

  input.addEventListener('keyup', debounce( function(e){
    let val = e.target.value;
    if (val === '' || val === prevValue) {
      messages.className = 'messages messages--status';
      messages.style.display = 'none';
      messages.innerHTML = 'Available';
    }
    else{
      landing_id = document.querySelector('input[name=landing_id]');
      landing_id = landing_id ? landing_id.value : '';

      jQuery.ajax({
        url: `${base_url}ajax/landing-master/checkdomain`,
        type: 'POST',
        data: {
          landing_id : landing_id,
          domain : val
        },
        dataType: 'JSON',
        success: function(response) {
          messages.style.display = 'none';
          setTimeout(function(){
            if (response.code === 200) {
              messages.className = 'messages messages--status';
              messages.style.display = 'block';
              messages.innerHTML = 'Available';
            }
            else{
              messages.className = 'messages messages--error';
              messages.style.display = 'block';
              messages.innerHTML = 'Not available';
            }
            e.target.value = response.data.domain;
          }, 70);
        }
      });

    }
  }, 1200));
}

// subdomain
if (document.querySelector('.wizard-landing #container-subdomain input')) {
  let messages = document.querySelector('.wizard-landing #container-subdomain .messages');
  let input = document.querySelector('.wizard-landing #container-subdomain input');
  let prevValue = input.value;

  input.addEventListener('keyup', debounce( function(e){
    let val = e.target.value;
    if (val === '' || val === prevValue) {
      messages.className = 'messages messages--status';
      messages.style.display = 'none';
      messages.innerHTML = 'Available';
    }
    else{
      landing_id = document.querySelector('input[name=landing_id]');
      landing_id = landing_id ? landing_id.value : '';

      jQuery.ajax({
        url: `${base_url}ajax/landing-master/checksubdomain`,
        type: 'POST',
        data: {
          landing_id : landing_id,
          subdomain : val
        },
        dataType: 'JSON',
        success: function(response) {
          messages.style.display = 'none';
          setTimeout(function(){
            if (response.code === 200) {
              messages.className = 'messages messages--status';
              messages.style.display = 'block';
              messages.innerHTML = 'Available';
            }
            else{
              messages.className = 'messages messages--error';
              messages.style.display = 'block';
              messages.innerHTML = 'Not available';
            }
            e.target.value = response.data.subdomain;
          }, 70);
        }
      });

    }
  }, 1200));
}
//form wizard landing
