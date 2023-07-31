
var product_category_list = document.getElementById('product-category-list');
if (product_category_list) {
  
  product_category_list = JSON.parse(product_category_list.value);

  const generate_specification_input = (title, name, value='') => {
    return  `
      <div class="row mt-4">
        <div class="form-group col-md-6">
          <label class="pb-2">${title}</label>
          <input type="text" class="form-control border" name="specification[${name}]" value="${value}" />
        </div>
      </div>
    `;
  }

  // data specification (for edit form)
  let specification_data = document.getElementById('product-specification-data');
  if (specification_data) {
    specification_data = JSON.parse(specification_data.value);
  }

  // oncange select category
  let select_category = document.getElementById('product-category-select');
  select_category.addEventListener('change', function(e){
    let category_detail = product_category_list[e.target.value];
  
    let input_html = '';
    for (let i = 0; i < category_detail.specification.length; i++) {

      let title = category_detail.specification[i];
      let name = category_detail.specification_formatted[i];
      let value = '';

      // if in edit form (have properti specification 'name', use the value to retrieve)
      if (specification_data && specification_data.specification[name] !== undefined) {
        value = specification_data.specification[name];
      }

      input_html += generate_specification_input( title, name, value );
    }
  
    document.getElementById('container-specification').innerHTML = input_html;
  
  });

  // for edit form : auto generate data list specification (for the first time)
  if (specification_data) {
    let category_detail = product_category_list[specification_data.category_id];
  
    let input_html = '';
    for (let i = 0; i < category_detail.specification.length; i++) {

      let title = category_detail.specification[i];
      let name = category_detail.specification_formatted[i];
      let value = '';

      // if in edit form (have properti specification 'name', use the value to retrieve)
      if (specification_data && specification_data.specification[name] !== undefined) {
        value = specification_data.specification[name];
      }

      input_html += generate_specification_input( title, name, value );
    }
  
    document.getElementById('container-specification').innerHTML = input_html;
  }

  const process_submit = (form_id) => {
  
    let form = document.getElementById(form_id);
    let fields = document.querySelectorAll(`#${form_id} .form-control`);
  
    if (fields.length > 0) {
      let is_filled = true;
      for (let i = 0; i < fields.length; i++) {
        if (fields[i].required && ! fields[i].value) {
          is_filled = false;
          alert('Title & category is required');
          break;
        }
      }
  
      if (is_filled) {
        form.submit();
      }
    }
  }
  
  const submitAdd = document.getElementById('submit-add-product');
  if (submitAdd) {
    submitAdd.addEventListener('click', function(e){
      e.preventDefault();
  
      process_submit('form-add-product');
    })
  }

  const submitEdit = document.getElementById('submit-edit-product');
  if (submitEdit) {
    submitEdit.addEventListener('click', function(e){
      e.preventDefault();
  
      process_submit('form-edit-product');
    })
  }
}