
function landing_form_onchange_type(event, options, oldValue) {
  
  let newValue = event.target.value;
  let type = options[newValue];
  
  event.target.value = oldValue; //keep

  if ( window.confirm('Change Landing Page Type? All changes will be lost.') ) {
    // query string type= is always exist (from node form alter). Redirect
    location.href = location.href.replace(/(type=)[^\&]+/, '$1' + encodeURIComponent(type.toLowerCase()));
  }

}

const landing_type_el = document.getElementById('edit-landing-page-type');
if (landing_type_el) {
  const CURRENT_TYPE = landing_type_el.value;
  const LIST_TYPE = {};
  for (let i = 0; i < landing_type_el.children.length; i++) {
    LIST_TYPE[landing_type_el.children[i].value] = landing_type_el.children[i].innerHTML;
  }
  
  landing_type_el.addEventListener('change', (e)=>landing_form_onchange_type(e, LIST_TYPE, CURRENT_TYPE));
}