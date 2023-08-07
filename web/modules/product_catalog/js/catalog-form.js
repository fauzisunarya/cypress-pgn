
function catalog_form_onchange_type(event, options, oldValue) {
  
  let newValue = event.target.value;
  let type = options[newValue];
  
  event.target.value = oldValue; //keep

  if ( window.confirm('Change Catalog Type? All changes will be lost.') ) {
    // query string type= is always exist (from node form alter). Redirect
    location.href = location.href.replace(/(type=)[^\&]+/, '$1' + encodeURIComponent(type.toLowerCase()) );
  }

}

const catalog_type_el = document.getElementById('edit-field-pct-type');
if (catalog_type_el) {
  const CURRENT_TYPE = catalog_type_el.value;
  const LIST_TYPE = {};
  for (let i = 0; i < catalog_type_el.children.length; i++) {
    LIST_TYPE[catalog_type_el.children[i].value] = catalog_type_el.children[i].innerHTML;
  }
  
  catalog_type_el.addEventListener('change', (e)=>catalog_form_onchange_type(e, LIST_TYPE, CURRENT_TYPE));
}