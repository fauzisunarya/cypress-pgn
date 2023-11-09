
// for sidebar logo
document.addEventListener('DOMContentLoaded', (e) => {
    let ckeditor  = document.querySelector("#image-tab-deskripsi");
    let logo_img  = document.querySelector("#sidebar-wrapper .sidebar-brand.d-flex.justify-content-center img");
    let link_logo = document.querySelector("#sidebar-wrapper .sidebar-brand.d-flex.justify-content-center");

    if (ckeditor) {
        buildCKEditor(ckeditor);
    }
    
    if (logo_img) {
        logo_img.alt = "telkom-logo";
        logo_img.className = "";
        logo_img.width = "40";
        logo_img.height = "40";
        logo_img.style.maxWidth = "none";
    }

    if (link_logo) {
        link_logo.className = "sidebar-brand d-flex justify-content-center link-dark text-decoration-none";
    }
})

function buildCKEditor(element){
    ClassicEditor
        .create( element, {
            // https://ckeditor.com/docs/ckeditor5/latest/features/toolbar/toolbar.html#extended-toolbar-configuration-format
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic','|',
                    'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'undo', 'redo',
                    '-',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                    'link', 'blockQuote', 'insertTable', 'mediaEmbed', '|',
                    'pageBreak', '|',
                    'sourceEditing'
                ],
                shouldNotGroupWhenFull: true
            }
        })
        .catch( error => {
            console.error( error );
        });
}

// start : detail paket or product catalog template list
function detailPaketGetTemplate(id, container_id, template_type) {

    let available_template = ['email', 'sms', 'whatsapp', 'facebook', 'instagram', 'twitter'];
    if (!available_template.includes(template_type)) {
        template_type = 'email'
    }

    let template_container = document.getElementById(container_id);
    if (id && template_container) {
        let baseUrl = document.getElementById('input_hidden_base_url').value ;
        jQuery.ajax({
            type : "post",
            url : baseUrl + '/ajax/template/get_template',
            data : {
                    id: id, 
                    template_type: template_type
                },
            success: function(response) {
                let template_list = '';
                response.template.forEach(template=>{
                    template_list += `<a class="nav-item-link" href="${template.url}">${template.title}</a>`;
                })
                template_container.innerHTML = `
                <div class="accordion-body">
                    ${template_list ? template_list : '<span class="nav-item-link">Empty</span>'}
                </div>
                `;
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', (e) => {
    let paket_id = document.getElementById('input_hidden_paket_id') ? document.getElementById('input_hidden_paket_id').value : false;
    let product_catalog_id = document.getElementById('input_hidden_product_catalog_id') ? document.getElementById('input_hidden_product_catalog_id').value : false;

    let id = paket_id ? paket_id : product_catalog_id ;

    // get the template and render in detail paket or product catalog
    detailPaketGetTemplate(id, 'templateEmail', 'email' );
    detailPaketGetTemplate(id, 'templateSms', 'sms' );
    detailPaketGetTemplate(id, 'templateWa', 'whatsapp' );
    detailPaketGetTemplate(id, 'templateFacebook', 'facebook' );
    detailPaketGetTemplate(id, 'templateInstagram', 'instagram' );
    detailPaketGetTemplate(id, 'templateTwitter', 'twitter' );
})
// end of : detail paket template list

// Start : Detail media preview
function openDetailBundleImagePreview(idx_bundle){
    let modal = document.getElementById('detailMediaPreview');
    if (modal) {
        modal.style.display = 'block';
        modal.classList.add('show');

        let bundle = response_media_bundle.content[idx_bundle]; // get media from global variable

        // fill the modal with media image data
        let carouselIndicators = document.querySelector('#carouselDetailMedia .carousel-indicators');
        let carouselInner = document.querySelector('#carouselDetailMedia .carousel-inner');
        if (carouselIndicators && carouselInner) {
            let innerIndicators = '';
            let innerContent = '';
            bundle.media.forEach((data, i)=>{
                if (i===0) {
                    innerIndicators += `<button type="button" data-bs-target="#carouselDetailMedia" data-bs-slide-to="${i}" class="active" aria-current="true" aria-label="Slide ${i+1}"></button>`

                    innerContent += `<div class="carousel-item active" style="text-align:center;"><img src="${data.image.url}" class="img-fluid" style="width:70%" alt="${data.image.alt}"></div>`
                }
                else{
                    innerIndicators += `<button type="button" data-bs-target="#carouselDetailMedia" data-bs-slide-to="${i}" aria-label="Slide ${i}"></button>`

                    innerContent += `<div class="carousel-item" style="text-align:center;"><img src="${data.image.url}" class="img-fluid" style="width:70%" alt="${data.image.alt}"></div>`
                }
            })
            carouselIndicators.innerHTML = innerIndicators;
            carouselInner.innerHTML = innerContent;
        }

        let previewTitle = document.getElementById('PreviewMediaTitle');
        if (previewTitle) {
            previewTitle.innerHTML = bundle.title;
        }
    }
}

function openDetailMediaImagePreview(idx_image){
    let modal = document.getElementById('detailMediaPreview');
    if (modal) {
        modal.style.display = 'block';
        modal.classList.add('show');

        let media = response_media.content[idx_image]; // get media from global media

        // fill the modal with media image data
        let carouselIndicators = document.querySelector('#carouselDetailMedia .carousel-indicators');
        let carouselInner = document.querySelector('#carouselDetailMedia .carousel-inner');
        if (carouselIndicators && carouselInner) {
            let innerIndicators = '';
            let innerContent = '';
            media.image.forEach((image, i)=>{
                if (i===0) {
                    innerIndicators += `<button type="button" data-bs-target="#carouselDetailMedia" data-bs-slide-to="${i}" class="active" aria-current="true" aria-label="Slide ${i+1}"></button>`

                    innerContent += `<div class="carousel-item active" style="text-align:center;"><img src="${image.url}" class="img-fluid" style="width:70%" alt="${image.alt}"></div>`
                }
                else{
                    innerIndicators += `<button type="button" data-bs-target="#carouselDetailMedia" data-bs-slide-to="${i}" aria-label="Slide ${i}"></button>`

                    innerContent += `<div class="carousel-item" style="text-align:center;"><img src="${image.url}" class="img-fluid" style="width:70%" alt="${image.alt}"></div>`
                }
            })
            carouselIndicators.innerHTML = innerIndicators;
            carouselInner.innerHTML = innerContent;
        }

        let previewTitle = document.getElementById('PreviewMediaTitle');
        if (previewTitle) {
            previewTitle.innerHTML = media.title;
        }
    }
}

function openDetailMediaVideoPreview(idx_video){
    let modal = document.getElementById('detailMediaPreview');
    if (modal) {
        modal.style.display = 'block';
        modal.classList.add('show');

        let media = response_video.content[idx_video]; // get media from global media

        // fill the modal with media image data
        let carouselIndicators = document.querySelector('#carouselDetailMedia .carousel-indicators');
        let carouselInner = document.querySelector('#carouselDetailMedia .carousel-inner');
        if (carouselIndicators && carouselInner) {
            let innerIndicators = '';
            let innerContent = '';
            media.video.forEach((video, i)=>{
                let active = '';
                if (i===0) {
                    innerIndicators += `<button type="button" data-bs-target="#carouselDetailMedia" data-bs-slide-to="${i}" class="active" aria-current="true" aria-label="Slide ${i+1}"></button>`
                    active = 'active';
                }
                else{
                    innerIndicators += `<button type="button" data-bs-target="#carouselDetailMedia" data-bs-slide-to="${i}" aria-label="Slide ${i}"></button>`
                }

                if (video.type==='file') {
                    innerContent += `
                        <div class="carousel-item ${active}" style="text-align:center;">
                            <video controls="controls" class="preview-media-video" style="width:70%;margin-bottom:15%;">
                                <source src="${video.url}" type="video/mp4">
                            </video>
                        </div>
                    `;
                }
                else if (video.type==='iframe') {
                    innerContent += `
                        <div class="carousel-item ${active}">
                            <div class="preview-media-video" style="text-align:center;margin-bottom:15%;">
                                ${video.iframe}
                            </div>
                        </div>
                    `;
                }
                
            })
            carouselIndicators.innerHTML = innerIndicators;
            carouselInner.innerHTML = innerContent;
        }

        let previewTitle = document.getElementById('PreviewMediaTitle');
        if (previewTitle) {
            previewTitle.innerHTML = media.title;
        }
    }
}

function closeDetailMediaPreview(){
    let modal = document.getElementById('detailMediaPreview');
    if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
    }
}

// add defaul content to detail preview
let detail_media_preview = document.getElementById('detailMediaPreview');
if (detail_media_preview) {
    detail_media_preview.innerHTML = `
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="PreviewMediaTitle">For Paket 3P (Internet+Phone+Tv)</h5>
                    <button type="button" class="btn-close" onclick="closeDetailMediaPreview()"></button>
                </div>
                <div class="modal-body">
                    <div id="carouselDetailMedia" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselDetailMedia" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselDetailMedia" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselDetailMedia" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="https://source.unsplash.com/1200x768?random" class="img-fluid" alt="...">
                            </div>
                            <div class="carousel-item">
                                <img src="https://source.unsplash.com/1200x768?random" class="img-fluid" alt="...">
                            </div>
                            <div class="carousel-item">
                                <img src="https://source.unsplash.com/1200x768?random" class="img-fluid" alt="...">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselDetailMedia" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselDetailMedia" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeDetailMediaPreview()">Close</button>
                </div>
            </div>
        </div>
    `;
}
// End : Detail media preview


// Start : Detail paket image Tab
// Note: product catalog also has image tab and also using this function, 
//       the difference is using catalog_id instead of paket_id and also different in url endpoint

var response_media = []; // to store the newest ajax image response
const params = new Proxy(new URLSearchParams(window.location.search), {
    get: (searchParams, prop) => searchParams.get(prop),
});

// Get the value of "some_key" in eg "https://example.com/?some_key=some_value"
let showed_element = params.showed_element; // "some_value"

// to repopolate the previous tab. ex: in detail paket tab image, after add image, the opening tab will be the image tab
document.addEventListener('DOMContentLoaded', (e)=>{
    e.preventDefault();
    if (showed_element) {
        let element = document.getElementById(showed_element);
        if(element && showed_element==='image-tab-edit'){ //edit button is inside the image tab
            let image_tab = document.getElementById('image-tab');
            if (image_tab) {
                image_tab.click();
                image_tab.scrollIntoView({behavior:'smooth', inline:'start'});
                element.scrollIntoView({behavior:'smooth', inline:'start'});
            }
        }
        else if (element) {
            element.click();
            element.scrollIntoView({behavior:'smooth', inline:'start'});
        }
    }
})

function debounce(func, timeout = 300){
  let timer;
  return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => { func.apply(this, args); }, timeout);
  };
}

let imageSearch = document.getElementById('image-tab-search');
if (imageSearch) {
    imageSearch.addEventListener('keyup', debounce( (e) => {

        let baseUrl = document.getElementById('input_hidden_base_url').value ;
        let search = e.target.value;

        document.getElementById('image-tab-search-icon').style.display = 'block';
        
        // get the image media
        if (response_media.paket_id || response_media.paket_id === null) {
            jQuery.ajax({
                type : "post",
                url : baseUrl + '/ajax/paket/get_image',
                data : {
                        paket_id: response_media.paket_id, 
                        category_id: response_media.category_id,
                        search
                    },
                success: function(response) {
                    // render the list image
                    response_media = response;
                    ImageResetForm(true, false);
                    renderImagePaket(response);

                    document.getElementById('image-tab-search-icon').style.display = 'none';
                }
            });
        }
        else if(response_media.catalog_id) {
            jQuery.ajax({
                type : "post",
                url : baseUrl + '/ajax/catalog/get_image',
                data : {
                        catalog_id: response_media.catalog_id, 
                        category_id: response_media.category_id,
                        search
                    },
                success: function(response) {
                    // render the list image
                    response_media = response;
                    ImageResetForm(true, false);
                    renderImagePaket(response);

                    document.getElementById('image-tab-search-icon').style.display = 'none';
                }
            });
        }
    }, 1000))
}

function imagePaginationRequestPage(page=1){
    // from input type hidden
    let baseUrl = document.getElementById('input_hidden_base_url').value ;
    let search = document.getElementById('image-tab-search') ? document.getElementById('image-tab-search').value : '';

    // get the image media
    if (response_media.paket_id || response_media.paket_id === null) {
        jQuery.ajax({
            type : "post",
            url : baseUrl + '/ajax/paket/get_image',
            data : {
                    paket_id: response_media.paket_id, 
                    category_id: response_media.category_id,
                    request_page: page,
                    search
                },
            success: function(response) {
                // render the list image
                response_media = response;
                ImageResetForm(true, false);
                renderImagePaket(response);
            }
        });
    }
    else if(response_media.catalog_id) {
        jQuery.ajax({
            type : "post",
            url : baseUrl + '/ajax/catalog/get_image',
            data : {
                    catalog_id: response_media.catalog_id, 
                    category_id: response_media.category_id,
                    request_page: page,
                    search
                },
            success: function(response) {
                // render the list image
                response_media = response;
                ImageResetForm(true, false);
                renderImagePaket(response);
            }
        });
    }
}

// to render custom pagination for image in detail paket
function addImagePagination(pagination){
    if (pagination.show===false) {
        return '';
    }

    let prev_el = pagination.prev===false ? '' : `
        <li class="w3-button pager__item pager__item--next li-0">
            <a onclick="imagePaginationRequestPage('${pagination.current_page-1}')" title="Go to previous page" rel="prev">
                <span class="visually-hidden">Previous page</span>
                <span aria-hidden="true"> < Previous </span>
            </a>
        </li>
    `;

    let next_el = pagination.next===false ? '' : `
        <li class="w3-button pager__item pager__item--next li-0">
            <a onclick="imagePaginationRequestPage('${pagination.current_page+1}')" title="Go to next page" rel="next">
                <span class="visually-hidden">Next page</span>
                <span aria-hidden="true">Next ›</span>
            </a>
        </li>
    `;

    let list_pages = '';
    pagination.available_pages.forEach(page=>{
        if (page===pagination.current_page) {
            list_pages += `
                <li class="w3-button pager__item w3-light-gray w3-active-pager is-active li-0">
                    <span title="Current page">
                        <span class="visually-hidden">Current page</span>
                        ${page}
                    </span>
                </li>
            `;
        }
        else{
            list_pages += `
                <li class="w3-button pager__item li-0">
                    <a onclick="imagePaginationRequestPage('${page}')" title="Go to page ${page}">
                        <span class="visually-hidden">Page</span>
                        ${page}
                    </a>
                </li>
            `;
        }
    });

    return `
    <nav class="w3-center pager" role="navigation" aria-labelledby="pagination-heading">
        <h4 id="pagination-heading" class="visually-hidden">Pagination</h4>
        <ul class="w3-bar pager__items js-pager__items ul-0">
            ${prev_el}
            ${list_pages}
            ${next_el}
        </ul>
    </nav>
    `;
}

// to render the category in right side form (page detail paket tab image)
function renderImageCategoryForm(arr_category){
    let category_el = '';
    arr_category.forEach(category=>{
        let checked = category.checked ? 'checked' : '';
        category_el += `
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="${category.name}" id="${category.element_id}" ${checked}>
                <label class="form-check-label" for="${category.element_id}">${category.label}</label>
            </div>
        `;
    })
    return category_el;
}

// to render the list of category to filter the list paket media image
function renderImageFilterCategory(arr_category){
    let option = '';
    arr_category.forEach(category=>{
        option += `<option value="${category.id}">${category.label}</option>`;
    })
    return option;
}

// to fill the form in right side
function clickImageChangeForm(idx_content){
    let media = response_media.content[idx_content]; //this is contain paket media detail

    // list category with selected option
    let link_url = '';
    let arr_category = Object.values(media.category);
    let category_el = renderImageCategoryForm(arr_category);
    let baseUrl = document.getElementById('input_hidden_base_url').value ;
    let request_uri = document.getElementById('input_hidden_request_uri').value ;
    let url_edit_image = `${baseUrl}/node/${media.id}/edit?destination=${request_uri}?showed_element=image-tab-edit`;

    // set form variable
    document.getElementById('image-tab-judul').value = media.title;
    document.getElementById('image-tab-redirect').value = media.redirect;
    document.getElementById('image-tab-kategori').innerHTML = `<label>Kategori</label>${category_el}`;
    document.getElementById('image-tab-media-id').value = media.id;
    document.getElementById('image-tab-edit').innerHTML = `<a href="${url_edit_image}" class="btn btn-warning">Edit Image</a>`;
    document.querySelector('.ck-editor__editable').ckeditorInstance.setData( media.description ? media.description : '' );  

    // set copy link url
    media.image.forEach((image, i)=>{
        link_url += `
            <div class="media-tab-link-item">
                <input type="text" id='image-tab-link-${i}' style='height: 50px;' value="${image.url}" class="form-control border pe-0" readonly>
                <div class="media-tooltip">
                    <button class="btn btn-info me-2 shadow" type="button" onclick="media_tooltip_copy('image-tab-link-${i}','media-tooltip-${i}')" onmouseenter="media_tooltip_in('media-tooltip-${i}')" onmouseout="media_tooltip_out('media-tooltip-${i}')">
                        <span class="media-tooltiptext" id="media-tooltip-${i}">Copy to clipboard</span>
                        Copy
                    </button>
                </div>
            </div>
        `;
    });
    document.getElementById('image-tab-link-container').innerHTML = `<label>Link</label> ${link_url}`;
}

// to reset the form in right side & form select filter category
function ImageResetForm(right_form=true, filter_category=true){

    // list category option
    let arr_category = Object.values(response_media.category);

    let category_el = renderImageCategoryForm(arr_category);
    
    if (right_form) {
        // reset right side form
        document.getElementById('image-tab-judul').value = '';
        document.getElementById('image-tab-kategori').innerHTML = `<label>Kategori</label>${category_el}`;
        document.getElementById('image-tab-deskripsi').value = '';
        document.getElementById('image-tab-media-id').value = '';
        document.getElementById('image-tab-edit').innerHTML = '';
        document.getElementById('image-tab-link-container').innerHTML = '';
    }
    
    if (filter_category) {
        // reset filter category form
        let category_filter = renderImageFilterCategory(arr_category);
        document.getElementById('image-tab-filter-category').innerHTML = `<option value="">Semua Kategori</option>${category_filter}`;
    }

}

// display the list paket image
function renderImagePaket(response){
    if (response.content.length===0) {
        document.getElementById('image-tab-container').innerHTML = `<div class="col-md-12"><div class="card justify-content-center align-items-center mt-5"><img src="${base_url}themes/custom/telkom_cms/assets/icons/no-image.png" width="200"><div class="card-body"><div class="card-text">There is no image</div></div></div></div>`;
        return;
    }

    let container = document.getElementById('image-tab-container');
    let list_image_element = '';
    response.content.forEach((media, idx_content) => {

        let image_showed = '';
        if (media.image.length > 1) {
            
            let btnIndicator = '';
            for (let i = 0; i < media.image.length; i++) {
                if (i===0) {
                    btnIndicator += `<button type="button" data-bs-target="#carouselIndicators_${media.id}" data-bs-slide-to="${i}" class="active" aria-current="true" aria-label="Slide ${i+1}"></button>`;
                }
                else{
                    btnIndicator += `<button type="button" data-bs-target="#carouselIndicators_${media.id}" data-bs-slide-to="${i}" aria-label="Slide ${i+1}"></button>`
                }
            }

            // add container carousel
            image_showed += `
                <div id="carouselIndicators_${media.id}" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        ${btnIndicator}
                    </div>
                    <div class="carousel-inner">
            `;

            // add image
            media.image.forEach((image, idx) => {
                let active = idx===0 ? 'active' : '';
                image_showed += `
                    <div class="carousel-item ${active}">
                        <img src="${image.url}" class="d-block w-100 card-img-top img-landing-page" alt="${image.alt}" />
                    </div>
                `;
            });

            // add closed div
            image_showed += `</div></div>`; //close the div
        }
        else{
            let image_obj = media.image.length>0 ? media.image[0] : {url: '', alt: ''};
            image_showed += `<img src="${image_obj.url}" class="card-img-top img-landing-page" alt="${image_obj.alt}" />`
        }

        list_image_element += `
            <div onclick='clickImageChangeForm("${idx_content}")' class="col-lg-4 col-md-6 card-landing-page pe-0 mb-3">
                <div class="card border">
                    <div class="card-info" onclick="openDetailMediaImagePreview('${idx_content}')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16">
                        <path fill="none" d="M0 0h24v24H0z" />
                        <path
                        d="M12 3c5.392 0 9.878 3.88 10.819 9-.94 5.12-5.427 9-10.819 9-5.392 0-9.878-3.88-10.819-9C2.121 6.88 6.608 3 12 3zm0 16a9.005 9.005 0 0 0 8.777-7 9.005 9.005 0 0 0-17.554 0A9.005 9.005 0 0 0 12 19zm0-2.5a4.5 4.5 0 1 1 0-9 4.5 4.5 0 0 1 0 9zm0-2a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"
                        fill="rgba(255,255,255,1)" />
                    </svg>
                    </div>
                    ${image_showed}
                    <div class="card-body">
                        <p id="paket_media_${media.id}" class="card-text">${media.title}</p>
                    </div>
                </div>
            </div>
        `;

    });
    container.innerHTML = list_image_element + addImagePagination(response.pagination);
}

// when opening image tab in detail paket
let paketImageTab = document.getElementById('image-tab');
let paketImageTab_loaded = false;
if (paketImageTab) {
    // from input type hidden
    let baseUrl = document.getElementById('input_hidden_base_url').value ;
    let paket   = document.getElementById('input_hidden_paket_id');
    let catalog = document.getElementById('input_hidden_product_catalog_id');

    if ((paket && paket.value) || (catalog && catalog.value)) {
        paketImageTab.addEventListener('click', (e) => {
            if (paketImageTab_loaded) {
                return;
            }
            
            // get image media in detail paket or product catalog
            if (paket && paket.value) {
                jQuery.ajax({
                    type : "post",
                    url : baseUrl + '/ajax/paket/get_image',
                    data : {
                        paket_id: paket.value, 
                    },
                    success: function(response) {
                        // render the list image
                        response_media = response;
                        ImageResetForm();
                        renderImagePaket(response);
        
                        paketImageTab_loaded = true;
                    }
                });
            }
            else if(catalog && catalog.value){
                jQuery.ajax({
                    type : "post",
                    url : baseUrl + '/ajax/catalog/get_image',
                    data : {
                        catalog_id: catalog.value, 
                    },
                    success: function(response) {
                        // render the list image
                        response_media = response;
                        ImageResetForm();
                        renderImagePaket(response);
        
                        paketImageTab_loaded = true;
                    }
                });
            };
        });
    }
    else{
        document.addEventListener('DOMContentLoaded', (e) => {
            jQuery.ajax({
                type : "post",
                url : baseUrl + '/ajax/paket/get_image',
                data : {},
                dataType: 'JSON',
                success: function(response) {
                    // render the list image
                    response_media = response;
                    ImageResetForm();
                    renderImagePaket(response);
    
                    paketImageTab_loaded = true;
                }
            });
        });
    };
    
}

// add event to filter category
function onChangeImageCategory(event){
    let category_id = event.target.value; // empty category_id, in backend, will show all item

    // from input type hidden
    let baseUrl = document.getElementById('input_hidden_base_url').value ;
    let paket = document.getElementById('input_hidden_paket_id');
    let catalog = document.getElementById('input_hidden_product_catalog_id');

    let search = document.getElementById('image-tab-search') ? document.getElementById('image-tab-search').value : '';
    
    // get image media in detail paket or product catalog
    if (paket) {
        jQuery.ajax({
            type : "post",
            url : baseUrl + '/ajax/paket/get_image',
            data : {
                    paket_id: paket.value, 
                    category_id,
                    search
                   },
            success: function(response) {
                // render the list image
                response_media = response;
                ImageResetForm(true, false);
                renderImagePaket(response);
            }
        });
    }
    else if(catalog){
        jQuery.ajax({
            type : "post",
            url : baseUrl + '/ajax/catalog/get_image',
            data : {
                    catalog_id: catalog.value, 
                    category_id,
                    search
                   },
            success: function(response) {
                // render the list image
                response_media = response;
                ImageResetForm(true, false);
                renderImagePaket(response);
            }
        });
    }
}

let filter_category_element = document.getElementById('image-tab-filter-category');
if (filter_category_element) {
    filter_category_element.addEventListener('change', e => onChangeImageCategory(e));
}

// Save Image in detail paket page
let imageTabSimpan = document.getElementById('image-tab-simpan');
if (imageTabSimpan) {
    imageTabSimpan.addEventListener('click', e => {
        
        let category = '';
        let media_id = document.getElementById('image-tab-media-id').value;
        let title = document.getElementById('image-tab-judul').value;
        let redirect = document.getElementById('image-tab-redirect').value;
        let description = document.querySelector('.ck-editor__editable').ckeditorInstance.getData();
        let baseUrl = document.getElementById('input_hidden_base_url').value;
        
        if (!media_id) {
            return;
        }

        // get identifier
        let idx_paket_media = response_media.content.findIndex(val => val.id === parseInt(media_id));

        // mapping option select
        Object.values(response_media.category).forEach(value=>{
            // get the current input value
            if (document.querySelector(`input#${value.element_id}`).checked) {
                category += category==='' ? value.id : ","+value.id;

                // change the json checked boolean in global response_media
                response_media.content[idx_paket_media].category[value.id].checked = true;
            }
            else{
                // change the json checked boolean in global response_media
                response_media.content[idx_paket_media].category[value.id].checked = false;
            }
        });

        // save the image media
        jQuery.ajax({
            type : "post",
            url : baseUrl + '/ajax/paket/save_image',
            data : {
                media_id,
                title,
                redirect,
                description,
                category
            },
            success: function(response) {
                if (response.status==='success') {
                    document.getElementById(`paket_media_${media_id}`).innerHTML = title;
                    // update response data
                    response_media.content[idx_paket_media].title = title;
                    response_media.content[idx_paket_media].redirect = redirect;
                    response_media.content[idx_paket_media].description = description;

                    Swal.fire({
                        position: 'center',
                        width: 400,
                        // height: 150,
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1200
                    });
                }
            }
        });
    });
}

// End : Detail paket image tab

// Start : Detail paket video tab
// Note: product catalog also has video tab and also using this function, 
//       the difference is using catalog_id instead of paket_id and also different in url endpoint

var response_video = []; // to store the newest video from ajax response

let videoSearch = document.getElementById('video-tab-search');
if (videoSearch) {
    videoSearch.addEventListener('keyup', debounce( (e) => {
        let search = e.target.value; // empty category_id, in backend, will show all item
        document.getElementById('video-tab-search-icon').style.display = 'block';

        // from input type hidden
        let baseUrl = document.getElementById('input_hidden_base_url').value ;
        let paket = document.getElementById('input_hidden_paket_id') ;
        let catalog = document.getElementById('input_hidden_product_catalog_id') ;

        
        // get the video media for paket or product catalog detail page
        if (paket) {
            jQuery.ajax({
                type : "post",
                url : baseUrl + '/ajax/paket/get_video',
                data : {
                        paket_id: paket.value, 
                        category_id: response_video.category_id,
                        search
                    },
                success: function(response) {
                    // render the list video
                    response_video = response;
                    videoResetForm(true, false);
                    renderVideoPaket(response);

                    document.getElementById('video-tab-search-icon').style.display = 'none';
                }
            });
        } 
        else if(catalog){
            jQuery.ajax({
                type : "post",
                url : baseUrl + '/ajax/catalog/get_video',
                data : {
                        catalog_id: catalog.value, 
                        category_id: response_video.category_id,
                        search
                    },
                success: function(response) {
                    // render the list video
                    response_video = response;
                    videoResetForm(true, false);
                    renderVideoPaket(response);

                    document.getElementById('video-tab-search-icon').style.display = 'none';
                }
            });
        }
    }, 1000))
}

function videoPaginationRequestPage(page=1){
    // from input type hidden
    let baseUrl = document.getElementById('input_hidden_base_url').value ;
    let search = document.getElementById('video-tab-search').value;

    // get the video media
    if (response_video.paket_id || response_video.paket_id === null) {
        jQuery.ajax({
            type : "post",
            url : baseUrl + '/ajax/paket/get_video',
            data : {
                    paket_id: response_video.paket_id, 
                    category_id: response_video.category_id,
                    request_page: page,
                    search
                },
            success: function(response) {
                // render the list video
                response_video = response;
                videoResetForm(true, false);
                renderVideoPaket(response);
            }
        });
    }
    else if(response_video.catalog_id) {
        jQuery.ajax({
            type : "post",
            url : baseUrl + '/ajax/catalog/get_video',
            data : {
                    catalog_id: response_video.catalog_id, 
                    category_id: response_video.category_id,
                    request_page: page,
                    search
                },
            success: function(response) {
                // render the list video
                response_video = response;
                videoResetForm(true, false);
                renderVideoPaket(response);
            }
        });
    }
}

// to render custom pagination for video in detail paket
function addVideoPagination(pagination){
    if (pagination.show===false) {
        return '';
    }

    let prev_el = pagination.prev===false ? '' : `
        <li class="w3-button pager__item pager__item--next li-0">
            <a onclick="videoPaginationRequestPage('${pagination.current_page-1}')" title="Go to previous page" rel="prev">
                <span class="visually-hidden">Previous page</span>
                <span aria-hidden="true"> < Previous </span>
            </a>
        </li>
    `;

    let next_el = pagination.next===false ? '' : `
        <li class="w3-button pager__item pager__item--next li-0">
            <a onclick="videoPaginationRequestPage('${pagination.current_page+1}')" title="Go to next page" rel="next">
                <span class="visually-hidden">Next page</span>
                <span aria-hidden="true">Next ›</span>
            </a>
        </li>
    `;

    let list_pages = '';
    pagination.available_pages.forEach(page=>{
        if (page===pagination.current_page) {
            list_pages += `
                <li class="w3-button pager__item w3-light-gray w3-active-pager is-active li-0">
                    <span title="Current page">
                        <span class="visually-hidden">Current page</span>
                        ${page}
                    </span>
                </li>
            `;
        }
        else{
            list_pages += `
                <li class="w3-button pager__item li-0">
                    <a onclick="videoPaginationRequestPage('${page}')" title="Go to page ${page}">
                        <span class="visually-hidden">Page</span>
                        ${page}
                    </a>
                </li>
            `;
        }
    });

    return `
    <nav class="w3-center pager" role="navigation" aria-labelledby="pagination-heading">
        <h4 id="pagination-heading" class="visually-hidden">Pagination</h4>
        <ul class="w3-bar pager__items js-pager__items ul-0">
            ${prev_el}
            ${list_pages}
            ${next_el}
        </ul>
    </nav>
    `;
}

// to render the category in right side form (page detail paket tab video)
function renderVideoCategoryForm(arr_category){
    let category_el = '';
    arr_category.forEach(category=>{
        let checked = category.checked ? 'checked' : '';
        category_el += `
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="${category.name}" id="${category.element_id}" ${checked}>
                <label class="form-check-label" for="${category.element_id}">${category.label}</label>
            </div>
        `;
    })
    return category_el;
}

// to render the list of category to filter the list paket media video
function renderVideoFilterCategory(arr_category){
    let option = '';
    arr_category.forEach(category=>{
        option += `<option value="${category.id}">${category.label}</option>`;
    })
    return option;
}

// to fill the form in right side
function clickVideoChangeForm(idx_content){
    video = response_video.content[idx_content]; //this is contain paket video detail
    
    // let baseUrl = document.getElementById('input_hidden_base_url').value ;
    // let form_el = document.getElementById('video-tab-form');

    // list category with selected option
    let arr_category = Object.values(video.category);
    let category_el = renderVideoCategoryForm(arr_category);

    document.getElementById('video-tab-judul').value = video.title;
    document.getElementById('video-tab-kategori').innerHTML = `<label>Kategori</label>${category_el}`;
    document.getElementById('video-tab-deskripsi').value = video.description;

    document.getElementById('video-tab-video-id').value = video.id;

    // set link edit
    let baseUrl = document.getElementById('input_hidden_base_url').value ;
    let request_uri = document.getElementById('input_hidden_request_uri').value ;
    let url_edit_video = `${baseUrl}/node/${video.id}/edit?destination=${request_uri}?showed_element=video-tab`;
    document.getElementById('video-tab-edit').innerHTML = `<a href="${url_edit_video}" class="btn btn-warning">Edit Video</a>`;

    // set copy link url
    let link_url = '';
    video.video.forEach((video, i)=>{
        if (video.type==='iframe') {
            video.url = video.iframe.replace(/^.+src="/, '').replace(/".+<\/iframe>$/,'').replace('embed\/','watch?v=');
        }
        link_url += `
            <div class="media-tab-link-item">
                <input type="text" id='image-tab-link-${i}' value="${video.url}" class="form-control w-80 border" readonly>
                <div class="media-tooltip">
                    <button type="button" onclick="media_tooltip_copy('image-tab-link-${i}','media-tooltip-${i}')" onmouseenter="media_tooltip_in('media-tooltip-${i}')" onmouseout="media_tooltip_out('media-tooltip-${i}')">
                        <span class="media-tooltiptext" id="media-tooltip-${i}">Copy to clipboard</span>
                        Copy
                    </button>
                </div>
            </div>
        `
    });
    document.getElementById('video-tab-link-container').innerHTML = `<label>Link</label> ${link_url}`;
}

// to reset the form in right side & form select filter category
function videoResetForm(right_form=true, filter_category=true){

    // list category option
    let arr_category = Object.values(response_video.category);

    let category_el = renderVideoCategoryForm(arr_category);
    
    if (right_form) {
        // reset right side form
        document.getElementById('video-tab-judul').value = '';
        document.getElementById('video-tab-kategori').innerHTML = `<label>Kategori</label>${category_el}`;
        document.getElementById('video-tab-deskripsi').value = '';
        document.getElementById('video-tab-video-id').value = '';
        document.getElementById('video-tab-edit').innerHTML = '';
        document.getElementById('video-tab-link-container').innerHTML = '';
    }
    
    if (filter_category) {
        // reset filter category form
        let category_filter = renderVideoFilterCategory(arr_category);
        document.getElementById('video-tab-filter-category').innerHTML = `<option value="">Semua Kategori</option>${category_filter}`;
    }

}

// display the list paket video
function renderVideoPaket(response){
    if (response.content.length===0) {
        document.getElementById('video-tab-container').innerHTML = `<div class="col-md-12"><div class="card justify-content-center align-items-center mt-5"><img src="${base_url}themes/custom/telkom_cms/assets/icons/no-image.png" width="200"><div class="card-body"><div class="card-text">There is no video</div></div></div></div>`;
        return;
    }

    let container = document.getElementById('video-tab-container');
    let list_video_element = '';
    response.content.forEach((video, idx_content) => {

        let video_showed = '';
        if (video.video.length > 1) {
            
            let btnIndicator = '';
            for (let i = 0; i < video.video.length; i++) {
                if (i===0) {
                    btnIndicator += `<button type="button" data-bs-target="#carouselIndicators_${video.id}" data-bs-slide-to="${i}" class="active" aria-current="true" aria-label="Slide ${i+1}"></button>`;
                }
                else{
                    btnIndicator += `<button type="button" data-bs-target="#carouselIndicators_${video.id}" data-bs-slide-to="${i}" aria-label="Slide ${i+1}"></button>`
                }
            }

            // add container carousel
            video_showed += `
                <div id="carouselIndicators_${video.id}" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        ${btnIndicator}
                    </div>
                    <div class="carousel-inner">
            `;

            // add video
            video.video.forEach((video, idx) => {
                let active = idx===0 ? 'active' : '';
                if (video.type==='file') {
                    video_showed += `
                        <div class="carousel-item ${active}">
                            <video controls="controls" class="media-video">
                                <source src="${video.url}" type="video/mp4">
                            </video>
                        </div>
                    `;
                } 
                else if(video.type==='iframe') {
                    video_showed += `
                        <div class="carousel-item ${active}">
                            <div class="media-video">
                                ${video.iframe}
                            </div>
                        </div>
                    `;
                }
            });

            // add closed div
            video_showed += `</div></div>`; //close the div
        }
        else{
            if (video.video.length===0) { //there is a node video, but not have file video or iframe video (empty) 
                video_showed += `
                    <div style="position:relative;height:155px;" class="media-video">
                        <p style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%)">No video</p>
                    </div>
                `;
            }
            else if (video.video[0].type==='file') {
                video_showed += `
                    <video controls="controls" class="media-video">
                        <source src="${video.video[0].url}" type="video/mp4">
                    </video>
                `;
            } 
            else if(video.video[0].type==='iframe') {
                video_showed += `
                    <div class="media-video">
                        ${video.video[0].iframe}
                    </div>
                `;
            }
        }

        list_video_element += `
                    <div onclick='clickVideoChangeForm("${idx_content}")' class="col-lg-4 col-md-6 card-landing-page pe-0 mb-3">
                        <div class="card border">
                            <div class="card-info" onclick="openDetailMediaVideoPreview('${idx_content}')">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16">
                                <path fill="none" d="M0 0h24v24H0z" />
                                <path
                                    d="M12 3c5.392 0 9.878 3.88 10.819 9-.94 5.12-5.427 9-10.819 9-5.392 0-9.878-3.88-10.819-9C2.121 6.88 6.608 3 12 3zm0 16a9.005 9.005 0 0 0 8.777-7 9.005 9.005 0 0 0-17.554 0A9.005 9.005 0 0 0 12 19zm0-2.5a4.5 4.5 0 1 1 0-9 4.5 4.5 0 0 1 0 9zm0-2a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"
                                    fill="rgba(255,255,255,1)" />
                                </svg>
                            </div>
                            ${video_showed}
                            <div class="card-body">
                                <p id="paket_video_${video.id}" class="card-text">${video.title}</p>
                            </div>
                        </div>
                    </div>`;

    });
    container.innerHTML = list_video_element + addVideoPagination(response.pagination);
}

// when opening video tab in detail paket
let paketVideoTab = document.getElementById('video-tab');
let paketVideoTab_loaded = false;
if (paketVideoTab) {
    paketVideoTab.addEventListener('click', (e)=>{
        if (paketVideoTab_loaded) {
            return;
        }

        // from input type hidden
        let baseUrl = document.getElementById('input_hidden_base_url').value ;
        let paket = document.getElementById('input_hidden_paket_id') ;
        let catalog = document.getElementById('input_hidden_product_catalog_id') ;
        
        // get the video media for paket or product catalog detail page
        if (paket) {
            jQuery.ajax({
                type : "post",
                url : baseUrl + '/ajax/paket/get_video',
                data : {
                        paket_id: paket.value, 
                       },
                success: function(response) {
                    // render the list video
                    response_video = response;
                    videoResetForm();
                    renderVideoPaket(response);
    
                    paketVideoTab_loaded = true;
                }
            });
        } 
        else if(catalog){
            jQuery.ajax({
                type : "post",
                url : baseUrl + '/ajax/catalog/get_video',
                data : {
                        catalog_id: catalog.value, 
                       },
                success: function(response) {
                    // render the list video
                    response_video = response;
                    videoResetForm();
                    renderVideoPaket(response);
    
                    paketVideoTab_loaded = true;
                }
            });
        }

    });
}

// add event to filter category
function onChangeVideoCategory(event){
    let category_id = event.target.value; // empty category_id, in backend, will show all item

    // from input type hidden
    let baseUrl = document.getElementById('input_hidden_base_url').value ;
    let paket = document.getElementById('input_hidden_paket_id') ;
    let catalog = document.getElementById('input_hidden_product_catalog_id') ;

    let search = document.getElementById('video-tab-search').value;
    
    // get the video media for paket or product catalog detail page
    if (paket) {
        jQuery.ajax({
            type : "post",
            url : baseUrl + '/ajax/paket/get_video',
            data : {
                    paket_id: paket.value, 
                    category_id,
                    search
                   },
            success: function(response) {
                // render the list video
                response_video = response;
                videoResetForm(true, false);
                renderVideoPaket(response);
            }
        });
    } 
    else if(catalog){
        jQuery.ajax({
            type : "post",
            url : baseUrl + '/ajax/catalog/get_video',
            data : {
                    catalog_id: catalog.value, 
                    category_id,
                    search
                   },
            success: function(response) {
                // render the list video
                response_video = response;
                videoResetForm(true, false);
                renderVideoPaket(response);
            }
        });
    }
}

let filter_video_category_element = document.getElementById('video-tab-filter-category');
if (filter_video_category_element) {
    filter_video_category_element.addEventListener('change', e => onChangeVideoCategory(e));
}

// Save Video in detail paket page
let videoTabSimpan = document.getElementById('video-tab-simpan');
if (videoTabSimpan) {
    videoTabSimpan.addEventListener('click', e=>{
        
        let title = document.getElementById('video-tab-judul').value;
        let description = document.getElementById('video-tab-deskripsi').value ;
        let category = '';
        let video_id = document.getElementById('video-tab-video-id').value;

        let baseUrl = document.getElementById('input_hidden_base_url').value;
        
        if (video_id==='') {
            return;
        }

        let idx_paket_video = '';
        Object.values(response_video.content).forEach((value,idx)=>{
            if (`${video_id}`===`${value.id}`) {
                idx_paket_video = idx;
            }
        })

        let category_option = Object.values(response_video.category);
        category_option.forEach(value=>{

            // get the current input value
            let element_id = value.element_id;
            let checked = document.querySelector(`input#${element_id}`).checked;

            if (checked) {
                category += category==='' ? value.id : ","+value.id;

                // change the json checked boolean in global response_video
                response_video.content[idx_paket_video].category[value.id].checked = true;
            }
            else{
                // change the json checked boolean in global response_video
                response_video.content[idx_paket_video].category[value.id].checked = false;
            }

            response_video.content[idx_paket_video].title = title;
            response_video.content[idx_paket_video].description = description;

        });

        // save the video
        jQuery.ajax({
            type : "post",
            url : baseUrl + '/ajax/paket/save_video',
            data : {
                    video_id,
                    title,
                    description,
                    category
                   },
            success: function(response) {
                if (response.status==='success') {
                    Swal.fire({
                        position: 'center',
                        width: 400,
                        // height: 150,
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1200
                    })
                    document.getElementById(`paket_video_${video_id}`).innerHTML = title;
                }
            }
        });
    });
}

// End : Detail paket video tab

// Start Media Bundle

var response_media_bundle = [];

let mediaBundleSearch = document.getElementById('media-bundle-search');
if (mediaBundleSearch) {
    mediaBundleSearch.addEventListener('keyup', debounce( (e) => {

        let search = e.target.value;
        document.getElementById('media-bundle-search-icon').style.display = 'block';
        
        // get the image media
        jQuery.ajax({
            type : "post",
            url : window.location.origin + '/ajax/get_image_bundle',
            data : {
                    category_id: response_media_bundle.category_id, //current category id
                    search
                },
            success: function(response) {
                // render the list image
                response_media_bundle = response;
                renderImageBundle(response);

                document.getElementById('media-bundle-search-icon').style.display = 'none';
            }
        });
    }, 1000))
}

function renderBundleCategoryFilter(element_id) {
    let option = '';

    let arr_category = Object.values(response_media_bundle.category);;
    arr_category.forEach(category=>{
        option += `<option value="${category.id}">${category.label}</option>`;
    })

    let el = document.getElementById(element_id);
    if (el) {
        el.innerHTML = `<option value="">Semua Kategori</option>${option}`;
    }
}

// to render custom pagination for image in detail paket
function addBundleImagePagination(pagination){
    if (pagination.show===false) {
        return '';
    }

    let prev_el = pagination.prev===false ? '' : `
        <li class="w3-button pager__item pager__item--next li-0">
            <a onclick="bundleMediaPaginationRequestPage('${pagination.current_page-1}')" title="Go to previous page" rel="prev">
                <span class="visually-hidden">Previous page</span>
                <span aria-hidden="true"> < Previous </span>
            </a>
        </li>
    `;

    let next_el = pagination.next===false ? '' : `
        <li class="w3-button pager__item pager__item--next li-0">
            <a onclick="bundleMediaPaginationRequestPage('${pagination.current_page+1}')" title="Go to next page" rel="next">
                <span class="visually-hidden">Next page</span>
                <span aria-hidden="true">Next ›</span>
            </a>
        </li>
    `;

    let list_pages = '';
    pagination.available_pages.forEach(page=>{
        if (page===pagination.current_page) {
            list_pages += `
                <li class="w3-button pager__item w3-light-gray w3-active-pager is-active li-0">
                    <span title="Current page">
                        <span class="visually-hidden">Current page</span>
                        ${page}
                    </span>
                </li>
            `;
        }
        else{
            list_pages += `
                <li class="w3-button pager__item li-0">
                    <a onclick="bundleMediaPaginationRequestPage('${page}')" title="Go to page ${page}">
                        <span class="visually-hidden">Page</span>
                        ${page}
                    </a>
                </li>
            `;
        }
    });

    return `
    <nav class="w3-center pager" role="navigation" aria-labelledby="pagination-heading">
        <h4 id="pagination-heading" class="visually-hidden">Pagination</h4>
        <ul class="w3-bar pager__items js-pager__items ul-0">
            ${prev_el}
            ${list_pages}
            ${next_el}
        </ul>
    </nav>
    `;
}

function bundleMediaPaginationRequestPage(page=1){
    // from input type hidden
    let baseUrl = window.location.origin;
    let search = document.getElementById('media-bundle-search').value;

    // get the image media
    jQuery.ajax({
        type : "post",
        url : baseUrl + '/ajax/get_image_bundle',
        data : {
                category_id: response_media_bundle.category_id, //current category id
                request_page: page,
                search
            },
        success: function(response) {
            // render the list image
            response_media_bundle = response;
            renderImageBundle(response);
        }
    });
}

function renderImageBundle(response){
    if (response.content.length===0) {
        document.getElementById('image-tab-container').innerHTML = `<div class="col-md-12"><div class="card justify-content-center align-items-center mt-5"><img src="${base_url}themes/custom/telkom_cms/assets/icons/no-image.png" width="200"><div class="card-body"><div class="card-text">There is no image</div></div></div></div>`;
        return;
    }

    // return console.log(response);
    let container = document.getElementById('image-tab-container');
    let list_image_element = '';
    if (container) {
        response.content.forEach((bundle, idx_bundle) => {

            let image_showed = '';
            if (bundle.media.length > 1) {
                
                let btnIndicator = '';
                for (let i = 0; i < bundle.media.length; i++) {
                    if (i===0) {
                        btnIndicator += `<button type="button" data-bs-target="#carouselIndicators_${bundle.id}" data-bs-slide-to="${i}" class="active" aria-current="true" aria-label="Slide ${i+1}"></button>`;
                    }
                    else{
                        btnIndicator += `<button type="button" data-bs-target="#carouselIndicators_${bundle.id}" data-bs-slide-to="${i}" aria-label="Slide ${i+1}"></button>`
                    }
                }

                // add container carousel
                image_showed += `
                    <div id="carouselIndicators_${bundle.id}" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            ${btnIndicator}
                        </div>
                        <div class="carousel-inner">
                `;

                // add image
                bundle.media.forEach((each_media, idx) => {
                    let active = idx===0 ? 'active' : '';
                    image_showed += `
                        <div class="carousel-item ${active}">
                            <img src="${each_media.image.url}" class="d-block w-100 card-img-top img-landing-page" alt="${each_media.image.alt}" />
                        </div>
                    `;
                });

                // add closed div
                image_showed += `</div>`; //close the div

                // add slider control to left right
                image_showed += `
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselIndicators_${bundle.id}" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselIndicators_${bundle.id}" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                `;

                // add closed div
                image_showed += `</div>`; //close the div
            }
            else{
                image_showed += `<img src="${bundle.media[0].image.url}" class="card-img-top img-landing-page" alt="${bundle.media[0].image.alt}" />`
            }

            list_image_element += `
                <div class="col-lg-3 col-md-4 card-landing-page pe-0 mb-3">
                    <div class="card border">
                        <div class="card-info" onclick="openDetailBundleImagePreview('${idx_bundle}')">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16">
                            <path fill="none" d="M0 0h24v24H0z" />
                            <path
                            d="M12 3c5.392 0 9.878 3.88 10.819 9-.94 5.12-5.427 9-10.819 9-5.392 0-9.878-3.88-10.819-9C2.121 6.88 6.608 3 12 3zm0 16a9.005 9.005 0 0 0 8.777-7 9.005 9.005 0 0 0-17.554 0A9.005 9.005 0 0 0 12 19zm0-2.5a4.5 4.5 0 1 1 0-9 4.5 4.5 0 0 1 0 9zm0-2a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"
                            fill="rgba(255,255,255,1)" />
                        </svg>
                        </div>
                        ${image_showed}
                        <div class="card-body">
                            <p class="card-text">${bundle.title}</p>
                        </div>
                        <div class="card-footer">
                            <a href="${bundle.link}" hreflang="en" class="btn btn-secondary">Read more</a>
                            <a href="/node/${bundle.id}/edit?destination=/media-bundle${window.location.href.replace(/^.+media-bundle/i,'')}" hreflang="en" class="btn btn-info">Edit</a>
                        </div>
                    </div>
                </div>
            `;

        });
        container.innerHTML = list_image_element + addBundleImagePagination(response.pagination);
    }
}

// initial get data list media bundle
if (window.location.href.includes('media-bundle')) {
    jQuery.ajax({
        type : "post",
        url : window.location.origin + '/ajax/get_image_bundle',
        data : {
                category_id: '', 
               },
        success: function(response) {
            // render the list image
            response_media_bundle = response;
            renderBundleCategoryFilter('image-tab-bundle-filter-category');
            renderImageBundle(response);
        }
    });
}

// add event to filter bundle category
function onChangeBundleCategory(event){
    let category_id = event.target.value; // empty category_id, in backend, will show all item
    let search = document.getElementById('media-bundle-search').value;

    jQuery.ajax({
        type : "post",
        url : window.location.origin + '/ajax/get_image_bundle',
        data : {
                category_id,
                search
        },
        success: function(response) {
            // render the list image
            response_media_bundle = response;
            renderImageBundle(response);
        }
    });
}

let bundle_category_filter = document.getElementById('image-tab-bundle-filter-category');
if (bundle_category_filter) {
    bundle_category_filter.addEventListener('change', e => onChangeBundleCategory(e));
}

// End of Media Bundle


// Start: paket setting template pricing

let btn_save_template_pricing_paket = document.getElementById('btn-save-template-pricing-paket');
if (btn_save_template_pricing_paket) {
    btn_save_template_pricing_paket.addEventListener('click', (e)=>{
        e.preventDefault();

        let list_setting = document.querySelectorAll('textarea.setting_template_pricing_paket');

        let data = [];
        for (let i = 0; i < list_setting.length; i++) {
            let setting_id = parseInt(list_setting[i].id.replace(/\D/g, ''));
            let value = list_setting[i].value;

            data.push({id:setting_id, value})
        }
        
        let base_url = document.getElementById('input_hidden_base_url').value;
        let paket_id = document.getElementById('input_hidden_paket_id').value;

        jQuery.ajax({
            type : "post",
            url : base_url + '/ajax/template_pricing_paket/save_setting',
            data : {
                    data,
                    paket_id
                },
            success: function(response) {
                if (response.status==='success') {
                    location.reload();
                    return false;
                }
            }
        });
    });
}

// Start product catalog setting template pricing
let btn_save_template_pricing_catalog = document.getElementById('btn-save-template-pricing-catalog');
if (btn_save_template_pricing_catalog) {
    btn_save_template_pricing_catalog.addEventListener('click', (e)=>{
        e.preventDefault();

        let list_setting = document.querySelectorAll('input.setting_template_pricing_catalog');
        // there is 2 input radio, show & hide with same name & class, process only if checked

        let data = [];
        for (let i = 0; i < list_setting.length; i++) {
            // process only if checked
            if(list_setting[i].checked){
                let setting_id = parseInt(list_setting[i].id.replace(/\D/g, ''));
                let value = list_setting[i].value;
                value = value==='true' ? true : false;
    
                data.push({id:setting_id, value})
            }
        }
        
        let base_url = document.getElementById('input_hidden_base_url').value;
        let product_catalog_id = document.getElementById('input_hidden_product_catalog_id').value;
        
        jQuery.ajax({
            type : "post",
            url : base_url + '/ajax/template_pricing_catalog/save_setting',
            data : {
                    data,
                    product_catalog_id
                },
            success: function(response) {
                if (response.status==='success') {
                    location.reload();
                    return false;
                }
            }
        });
    });
}

// start template pricing tooltip copy
function pricing_tooltip_copy(id_textarea, id_tooltip) {
    let copyText = document.getElementById(id_textarea);
    if (copyText) {

        copyText.select();
        copyText.setSelectionRange(0, 99999);

        // navigator clipboard api needs a secure context (https)
        if (navigator.clipboard && window.isSecureContext) {
            // navigator clipboard api method'
            navigator.clipboard.writeText(copyText.value);
        } 
        else {
            // text area method
            let textArea = document.createElement("textarea");
            textArea.value = copyText.value;
            // make the textarea out of viewport
            textArea.style.position = "fixed";
            textArea.style.left = "-999999px";
            textArea.style.top = "-999999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            new Promise((res, rej) => {
                // here the magic happens
                document.execCommand('copy') ? res() : rej();
                textArea.remove();
            });
        }
    
        let tooltip = document.getElementById(id_tooltip);
        if (tooltip) {
            tooltip.innerHTML = "Copied";
        }
    }
}

function pricing_tooltip_out(id_tooltip) {
    let tooltip = document.getElementById(id_tooltip);
    if (tooltip) {
        tooltip.innerHTML = "Copy to clipboard";

        tooltip.style.visibility = 'hidden';
        tooltip.style.opacity = '0';
    }
}

function pricing_tooltip_in(id_tooltip) {
    let tooltip = document.getElementById(id_tooltip);
    if (tooltip) {
        tooltip.style.visibility = 'visible';
        tooltip.style.opacity = '1';
    }
}
// end of template pricing tootip copy

// start media tooltip copy
function media_tooltip_copy(id_input, id_tooltip) {
    let copyText = document.getElementById(id_input);
    if (copyText) {

        copyText.select();
        copyText.setSelectionRange(0, 99999);

        // navigator clipboard api needs a secure context (https)
        if (navigator.clipboard && window.isSecureContext) {
            // navigator clipboard api method'
            navigator.clipboard.writeText(copyText.value);
        } 
        else {
            // text area method
            let textArea = document.createElement("textarea");
            textArea.value = copyText.value;
            // make the textarea out of viewport
            textArea.style.position = "fixed";
            textArea.style.left = "-999999px";
            textArea.style.top = "-999999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            new Promise((res, rej) => {
                // here the magic happens
                document.execCommand('copy') ? res() : rej();
                textArea.remove();
            });
        }
    
        let tooltip = document.getElementById(id_tooltip);
        if (tooltip) {
            tooltip.innerHTML = "Copied";
        }
    }
}

function media_tooltip_out(id_tooltip) {
    let tooltip = document.getElementById(id_tooltip);
    if (tooltip) {
        tooltip.innerHTML = "Copy to clipboard";

        tooltip.style.visibility = 'hidden';
        tooltip.style.opacity = '0';
    }
}

function media_tooltip_in(id_tooltip) {
    let tooltip = document.getElementById(id_tooltip);
    if (tooltip) {
        tooltip.style.visibility = 'visible';
        tooltip.style.opacity = '1';
    }
}
// end of media tootip copy

function get_landing_shortlink(id){

    let node_id = id
    jQuery.ajax({
        type : "post",
        url : window.location.origin + `/ajax/get/shortlink`,
        data : {
                node_id : node_id
            },
        success: function(response) {

            if (response.shortlink) {
                let link = response.shortlink;

                // navigator clipboard api needs a secure context (https)
                if (navigator.clipboard && window.isSecureContext) {
                    // navigator clipboard api method'
                    navigator.clipboard.writeText(link);
                } 
                else {
                    // text area method
                    let textArea = document.createElement("textarea");
                    textArea.value = link;
                    // make the textarea out of viewport
                    textArea.style.position = "fixed";
                    textArea.style.left = "-999999px";
                    textArea.style.top = "-999999px";
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    new Promise((res, rej) => {
                        // here the magic happens
                        document.execCommand('copy') ? res() : rej();
                        textArea.remove();
                    });
                }
            
                Swal.fire({
                    position: 'center',
                    width: 400,
                    // height: 150,
                    icon: 'success',
                    title: 'Copied',
                    showConfirmButton: false,
                    timer: 1200
                })
            }
        }
    });
}

let landing_get_shortlink = document.querySelectorAll('.landing_get_link');
if (landing_get_shortlink.length>0) {
    landing_get_shortlink.forEach(landing=>{
        let id = landing.id.split('node_')[1];
        landing.addEventListener('click', (e)=>{e.preventDefault(); get_landing_shortlink(id)})
    })
}

// start change action edit and delete to icons
let link_edit_or_delete = document.querySelectorAll('.table-action a');
if (link_edit_or_delete.length>0) {
    link_edit_or_delete.forEach(link=>{
        let text = link.innerHTML.toLowerCase().trim();
        if (text==='edit') {
            link.innerHTML = "<i class='bi bi-pencil'></i>"
        }
        else if(text==='delete') {
            link.innerHTML = "<i class='bi bi-trash'></i>"
        }
        else if(text==='lihat daftar') {
            link.innerHTML = "<i class='bi bi-eye'></i>"
        }
        else if(text==='instagram') {
            link.innerHTML = "<i class='bi bi-instagram'></i>"
        }
        else if(text==='twitter') {
            link.innerHTML = "<i class='bi bi-twitter'></i>"
        }
        else if(text==='facebook') {
            link.innerHTML = "<i class='bi bi-facebook'></i>"
        }
        else if(text==='whatsapp') {
            link.innerHTML = "<i class='bi bi-whatsapp'></i>"
        }
        else if(text==='sms') {
            link.innerHTML = "<i class='bi bi-chat-dots-fill'></i>"
        }
        else if(text==='email') {
            link.innerHTML = "<i class='bi bi-envelope-fill'></i>"
        }
    })
}
// end of change action edit and delete to icons

// start approval popup confirm form
function approve(){
    let message = document.getElementById('approval-message-form').value.replace(/<\/?[^>]+(>|$)/g, "");
    let current_url = document.getElementById('approval-current-url').value;
    let title = document.getElementById('approval-content-title').value;

    let destination = current_url + `/approve?message=${message}`;
    Swal.fire({
        title: `Approve "${title}"?`,
        html:  ``,
        confirmButtonText: 'Approve',
        confirmButtonColor: 'rgb(0, 128, 0)',
        showCancelButton: true,
        focusConfirm: false
    })
    .then((result) => {
        if (result.value) {
            window.location.href = destination; 
        }
    })
}

function reject(){
    let message = document.getElementById('approval-message-form').value.replace(/<\/?[^>]+(>|$)/g, "");
    let current_url = document.getElementById('approval-current-url').value;
    let title = document.getElementById('approval-content-title').value;

    if (message.length===0) {
        document.getElementById('approval-error-message').innerHTML = 'Message is required when reject';
    }
    else{
        let destination = current_url + `/reject?message=${message}`;
        Swal.fire({
            title: `Reject "${title}"?`,
            html:  ``,
            confirmButtonText: 'Reject',
            confirmButtonColor: '#dc3741',
            showCancelButton: true,
            focusConfirm: false
        })
        .then((result) => {
            if (result.value) {
                window.location.href = destination; 
            }
        })
    }
}

function showPopupApprove(id, title){
    Swal.fire({
        title: `Approve "${title}"?`,
        html:  `<textarea id="message-${id}" class="swal2-textarea" placeholder="Message"></textarea>`,
        confirmButtonText: 'Approve',
        confirmButtonColor: 'rgb(0, 128, 0)',
        showCancelButton: true,
        focusConfirm: false,
        preConfirm: () => {
            const message = Swal.getPopup().querySelector(`#message-${id}`).value;
            return { message: message }
        }
    })
    .then((result) => {
        window.location.href = `/approval/${id}/approve?message=` + encodeURIComponent(result.value.message).replace('%20','+'); 
    })
}
function showPopupReject(id, title){
    Swal.fire({
        title: `Reject "${title}"?`,
        html:  `<textarea id="message-${id}" class="swal2-textarea" placeholder="Message"></textarea>`,
        confirmButtonText: 'Reject',
        confirmButtonColor: '#dc3741',
        showCancelButton: true,
        focusConfirm: false,
        preConfirm: () => {
            const message = Swal.getPopup().querySelector(`#message-${id}`).value.replace(/<\/?[^>]+(>|$)/g, "");
            if (message.length===0) {
                Swal.showValidationMessage(`Message is required`)
              }
            return { message: message }
        }
    })
    .then((result) => {
        window.location.href = `/approval/${id}/reject?message=` + encodeURIComponent(result.value.message).replace('%20','+'); 
    })
}
let arr_approve = document.querySelectorAll('.approve-node');
if (arr_approve.length>0) {
    for (let i = 0; i < arr_approve.length; i++) {
        let id = arr_approve[i].id.replace('approve-node-','');
        let title = document.querySelector(`#title-node-${id} a`).innerHTML;
        arr_approve[i].addEventListener('click',(event)=> {event.preventDefault(); showPopupApprove(id, title)});
    }
}
let arr_reject = document.querySelectorAll('.reject-node');
if (arr_reject.length>0) {
    for (let i = 0; i < arr_reject.length; i++) {
        let id = arr_reject[i].id.replace('reject-node-','');
        let title = document.querySelector(`#title-node-${id} a`).innerHTML;
        arr_reject[i].addEventListener('click',(event)=> {event.preventDefault(); showPopupReject(id, title)});
    }
}
// end of approval popup confirm form

// approval external request
function processRequest(){
    let message = document.getElementById('ex-request-message-form').value.replace(/<\/?[^>]+(>|$)/g, "");
    let content_id = document.getElementById('ex-request-id').value;
    let title = document.getElementById('ex-request-title').value;

    let current_url = window.location.pathname
    let destination = window.location.origin + `/node/${content_id}/edit?progress_type=process&message=${message}&destination=${current_url}`;
    Swal.fire({
        title: `Process "${title}"?`,
        html:  ``,
        confirmButtonText: 'Process',
        confirmButtonColor: 'rgb(0, 128, 0)',
        showCancelButton: true,
        focusConfirm: false
    })
    .then((result) => {
        if (result.value) {
            window.location.href = destination; 
        }
    })
}

function rejectRequest(){
    let message = document.getElementById('ex-request-message-form').value.replace(/<\/?[^>]+(>|$)/g, "");
    let content_id = document.getElementById('ex-request-id').value;
    let title = document.getElementById('ex-request-title').value;

    if (message.length===0) {
        document.getElementById('ex-request-error-message').innerHTML = 'Message is required when reject';
    }
    else{
        let destination = window.location.origin + `/external_request/${content_id}/reject?message=${message}`;
        Swal.fire({
            title: `Reject "${title}"?`,
            html:  ``,
            confirmButtonText: 'Reject',
            confirmButtonColor: '#dc3741',
            showCancelButton: true,
            focusConfirm: false
        })
        .then((result) => {
            if (result.value) {
                window.location.href = destination; 
            }
        })
    }
}

function updateRequest(){
    let message = document.getElementById('ex-request-message-form').value.replace(/<\/?[^>]+(>|$)/g, "");
    let content_id = document.getElementById('ex-request-id').value;
    let title = document.getElementById('ex-request-title').value;

    if (message.length===0) {
        document.getElementById('ex-request-error-message').innerHTML = 'Message is required when update';
    }
    else{
        let current_url = window.location.pathname
        let destination = window.location.origin + `/node/${content_id}/edit?progress_type=update&message=${message}&destination=${current_url}`;
        Swal.fire({
            title: `Update "${title}"?`,
            html:  ``,
            confirmButtonText: 'Update',
            confirmButtonColor: 'rgb(0, 128, 0)',
            showCancelButton: true,
            focusConfirm: false
        })
        .then((result) => {
            if (result.value) {
                window.location.href = destination; 
            }
        })
    }
}

function doneRequest(){
    let message = document.getElementById('ex-request-message-form').value.replace(/<\/?[^>]+(>|$)/g, "");
    let content_id = document.getElementById('ex-request-id').value;
    let title = document.getElementById('ex-request-title').value;

    let current_url = window.location.pathname
    let destination = window.location.origin + `/node/${content_id}/edit?progress_type=done&message=${message}&destination=${current_url}`;
    Swal.fire({
        title: `Done "${title}"?`,
        html:  ``,
        confirmButtonText: 'Done',
        confirmButtonColor: 'rgb(0, 113, 184)',
        showCancelButton: true,
        focusConfirm: false
    })
    .then((result) => {
        if (result.value) {
            window.location.href = destination; 
        }
    })
}

// end of approval external request

// hide row weight
(function ($) {
    if (typeof Drupal!=="undefined" && Drupal.tableDrag) {
        // Copy hideColumns() method
        var hideColumns = Drupal.tableDrag.prototype.hideColumns;
        Drupal.tableDrag.prototype.hideColumns = function() {
          // Call the original hideColumns() method
          hideColumns.call(this);
          // Remove the 'Show row weights' string
          document.querySelectorAll('.tabledrag-toggle-weight').forEach(value=>{
            value.style.display = 'none';
          });
        }
      
        // Copy showColumns() method
        var showColumns = Drupal.tableDrag.prototype.showColumns;
        Drupal.tableDrag.prototype.showColumns = function () {
          // Call the original showColumns() method
          showColumns.call(this);
          // Remove the 'Hide row weights' string
          document.querySelectorAll('.tabledrag-toggle-weight').forEach(value=>{
            value.style.display = 'none';
          });
        }
    }
  })(jQuery);