// scripts for custom form in landing page

// leaflet map script
var mapLanding;
if (document.getElementById('formCoordinateMap')) {
  let inputCoordinate = document.getElementById('formCoordinateValue');

  setTimeout(() => {
    // disabled change input value
    inputCoordinate.addEventListener('keydown', function(e){
        e.preventDefault();
    });
  }, 50);

  mapLanding = L.map('formCoordinateMap').setView([-6.200000, 106.816666], 8);

  let tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
  }).addTo(mapLanding);

  let searchLayer = L.control.search();
  mapLanding.addControl(searchLayer);

  // add marker coordinate & set marker to 1
  function addMarkerCoordinate(lat, lng){
    //custom id for marker
    let marker_id = 'pin-map-id';

    for(let i in mapLanding._layers){
      //remove previous marker
      if(mapLanding._layers[i] && mapLanding._layers[i]._id === marker_id){
        mapLanding.removeLayer(mapLanding._layers[i]);
      }
    }

    //define marker
    let marker = L.marker([lat, lng], {draggable: true});
    marker._id = marker_id;

    marker.on('dragstart', function(e) {
      mapLanding.off('click', onMapClick);
    });
    marker.on('dragend', function(e) {
      inputCoordinate.value = `${e.target._latlng.lat},${e.target._latlng.lng}`;
      setTimeout(function() {
        mapLanding.on('click', onMapClick);
      }, 10);
    });

    // add value to input
    inputCoordinate.value = `${lat},${lng}`;

    // add marker to map
    marker.addTo(mapLanding);
  }

  mapLanding.on('geofound', function (e) {
    // this is fired from leaflet-search. if marker == null, this is first search. pin map on first search using map.on('layeradd', callback)
    if (mapLanding.marker) {
      // this is to set the newest pin map
      addMarkerCoordinate(e.latlng.lat, e.latlng.lng);
    }
  });

  mapLanding.on('layeradd', function(e) {
    // replace marker that not have specific id "pin-map-id" with function addMarkerCoordinate
    let data = { delete: null, lat: null, lng: null};
    for(let i in mapLanding._layers){
      if(mapLanding._layers[i] && mapLanding._layers[i]._latlng && mapLanding._layers[i]._id != 'pin-map-id'){
        data.delete = i;
        data.lat = mapLanding._layers[i]._latlng.lat;
        data.lng = mapLanding._layers[i]._latlng.lng;
      }
    }

    if (data.delete) {
      mapLanding.removeLayer(mapLanding._layers[data.delete]);
      addMarkerCoordinate(data.lat, data.lng);
    }
  });

  function onMapClick(e) {
    addMarkerCoordinate(e.latlng.lat, e.latlng.lng);
  }
  mapLanding.on('click', onMapClick);

  // search by input address textarea
  let addressInput = document.getElementById('formCoordinateAddress');
  if (addressInput) {
    let check_interval = null;
    addressInput.addEventListener('keyup', function(e){
      let input_value = e.target.value.trim();
      clearInterval(check_interval);
      check_interval = setInterval(() => {
        clearInterval(check_interval);

        if (input_value) {
          jQuery.ajax({
            type : "GET",
            url : "https://nominatim.openstreetmap.org/search?format=json&q="+encodeURI(input_value),
            headers: {
              'Accept':'*/*',
            },
            success: function(response) {
              if (response.length>0) {
                addMarkerCoordinate(response[0].lat, response[0].lon);
                mapLanding.setView([response[0].lat, response[0].lon], 15);
              }
            }
          });
        }

      }, 2000);
    });
  }

  // style for popup success message
  let message = document.querySelector('.success-message.is-cover');
  if (message) {
    message.style.zIndex = 1000;
  }
}
// end of leaflet map script

// Signature
if (document.getElementById('sig-field')) {
  let sig = $('#sig-field').signature({syncField: '#sig-field-value', syncFormat: 'PNG'});
  $('#clear-sig').click(function(e) {
      e.preventDefault();
      sig.signature('clear');
      $("#sig-field-value").val('');
  });
}
// end of Signature

// onchange file convert to base64
function convertInsertBase64(file, textarea_el){
  if (file) {
    const reader = new FileReader();
    reader.onload = function(event) {
      if (textarea_el) {
        textarea_el.innerHTML = event.target.result;
      }
    };
    reader.readAsDataURL(file);
  }
}

let inputfile = document.querySelectorAll('.icl-form input[type=file]');
if (inputfile.length>0) {
  inputfile.forEach(file=>{
    file.addEventListener('change', function(e){
      let files = e.target.files;
      if (files.length>0 && files[0].size < 10240000) {
        let textarea_id = `value-file-${e.target.id.replace('input-file-','')}`
        let el = document.getElementById(textarea_id);
        if (el) {
          convertInsertBase64(files[0], el);
        }
      }
    });
  });
}
// end of onchange file convert to base64

// capture KTP & Selfie
var videoStreamMode = 'environment';
function cameraAction(video, canvas, btnOpenCam, btnCapture, input, frame, btnChangeCam) {
  if (video && canvas && btnOpenCam && btnCapture) {
    if ('mediaDevices' in navigator && 'getUserMedia' in navigator.mediaDevices) {
      // ok, browser supports it
      let stream = null;
      let is_openCam = false;

      btnOpenCam.addEventListener('click', async function() {
        if (is_openCam===false) {
          try {
            stream = await navigator.mediaDevices.getUserMedia({
              video: {
                facingMode: videoStreamMode
              }
            });
            video.srcObject = stream;
  
            is_openCam = true;
  
            canvas.style.display = 'none';
            video.style.display = 'block';
            btnOpenCam.style.display = 'none';
            btnCapture.style.display = 'inline-block';
            btnChangeCam.style.display = 'inline-block';
            frame.style.display = 'block';
  
          } catch (error) {
            console.log('error open cam', error);
          }
        }
      })

      btnCapture.addEventListener('click', async function() {
        if (is_openCam) {
          canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
          input.innerHTML = canvas.toDataURL();
  
          if (stream) {
            stream.getTracks().forEach(function(track) {
              track.stop();
            });
          }
  
          is_openCam = false;
  
          canvas.style.display = 'block';
          video.style.display = 'none';
          btnOpenCam.style.display = 'block';
          btnCapture.style.display = 'none';
          btnChangeCam.style.display = 'none';
          frame.style.display = 'none';
        }
      })

      btnChangeCam.addEventListener('click', async function(){
        if (is_openCam) {
          videoStreamMode = videoStreamMode === 'environment' ? 'user' : 'environment';

          try {
            // close & capture other camera (other block)
            let isBlockKtp = btnChangeCam.id.includes('ktp'); // id = 'ktp-change-cam' or 'selfie-change-cam'
            let idChangeCam = isBlockKtp ? btnChangeCam.id.replace('ktp', 'selfie') : btnChangeCam.id.replace('selfie', 'ktp');
            let otherBtnChangeCam = document.getElementById(idChangeCam);
            if (otherBtnChangeCam && otherBtnChangeCam.style.display==='inline-block') { // indicates camera is open
              let idCapture = isBlockKtp ? btnCapture.id.replace('ktp', 'selfie') : btnCapture.id.replace('selfie', 'ktp');
              document.getElementById(idCapture).click();
            }

            // switch camera for this block
            if (stream) {
              stream.getTracks().forEach(function(track) {
                track.stop();
              });
              is_openCam = false;
            }
            btnOpenCam.click();

          } catch (error) {
            console.log('error change cam', error);
          }
        }
      });
    }
    else{
      // reset to input file (not open camera)
      // input.parentNode.innerHTML = `<input name='${input.name}' class='input-file' type='file' ${input.required ? 'required' : ''} style='line-height:1.1rem' disabled/>`;
      input.parentNode.innerHTML = `<p>your browser doesn't support camera</p>`;
    }
  }
}
let ktpVideo = document.getElementById('ktp-field-video');
let ktpCanvas = document.getElementById('ktp-field-canvas');
let ktpOpenCam = document.getElementById('ktp-field-opencam');
let ktpCapture = document.getElementById('ktp-field-capture');
let ktpInput = document.getElementById('ktp-field-value');
let ktpFrame = document.getElementById('ktp-field-frame');
let ktpChangeCam = document.getElementById('ktp-change-cam');

cameraAction(ktpVideo, ktpCanvas, ktpOpenCam, ktpCapture, ktpInput, ktpFrame, ktpChangeCam);

// end of capture ktp

// capture selfie
let selfieVideo = document.getElementById('selfie-field-video');
let selfieCanvas = document.getElementById('selfie-field-canvas');
let selfieOpenCam = document.getElementById('selfie-field-opencam');
let selfieCapture = document.getElementById('selfie-field-capture');
let selfieInput = document.getElementById('selfie-field-value');
let selfieFrame = document.getElementById('selfie-field-frame');
let selfieChangeCam = document.getElementById('selfie-change-cam');

cameraAction(selfieVideo, selfieCanvas, selfieOpenCam, selfieCapture, selfieInput, selfieFrame, selfieChangeCam);

// end of capture selfie

// disabled input
function disabledInput(arrId) {
  if (typeof arrId === 'object') {
    let counter = 0;
    let loop = setInterval(() => {
      counter++;
      if (counter === 100) {
        clearInterval(loop);
      }
      arrId.forEach(id => {
        let el = document.getElementById(id);
        if (el) {
          // disabled change input value
          let eventType = ['keydown', 'change', 'input', 'click', 'mousedown'];
          eventType.forEach(type=>{
            el.addEventListener(type, function(e){
              e.preventDefault();
            })
          });
        }
      });
    }, 50);
  }
}
// end of disabled input

// disabled input package
disabledInput(['inputPackage']);


//compare product
let select_1 = document.getElementById('select-product-1');
let select_2 = document.getElementById('select-product-2');
let select_3 = document.getElementById('select-product-3');

select_1.addEventListener('change', function(e){
  let select_satu = select_1.value
  if (select_satu == "") {
    document.getElementById('product-2').style.display = "none";
    document.getElementById('product-3').style.display = "none";
    document.getElementById('compare').style.display = "none";
  }else {
    document.getElementById('product-2').style.display = "block";
    // document.getElementById('compare').style.display = "block";
  }

})

select_2.addEventListener('change', function(e){
  let select_dua = select_2.value
  if (select_dua == "") {
    document.getElementById('product-3').style.display = "none";
    document.getElementById('compare').style.display = "none";
  }else {
    document.getElementById('product-3').style.display = "block";
    document.getElementById('compare').style.display = "block";
  }
})

let buttonCompare = document.querySelector(".comparation-1 button#compare");
if (buttonCompare) {
  buttonCompare.addEventListener("click", function(){
    let select1 = select_1
    let select2 = select_2
    let select3 = select_3
    let url = document.getElementById('ajax_url').value;

    jQuery.ajax({
      type : "POST",
      url : url + '/landing-page/compare-product',
      data: {
        product: [select1.value, select2.value, select3.value],
      },
      dataType: "JSON",
      success: function(response) {
        
        if (response.product.length >=2) {

          const rowCompareProduct = (labelKey, labelName, products) => {
            let td = '';
            for (let i = 0; i < response.product.length; i++) {
              td += `<td>${products[i][labelKey]}</td>`;
            }
            return `
              <tr>
                <td>${labelName}</td>
                ${td}
              </tr>
            `;
          }

          let th = '<th></th>';
          for (let i = 0; i < response.product.length; i++) {
            th += `<th>Produk ${i+1}</th>`;
          }

          let tbody = '';
          for (const labelKey in response.label) {
            tbody += rowCompareProduct(labelKey, response.label[labelKey], response.product);
          }
          
          document.getElementById('table-product-comparation').innerHTML = `
            <thead>
              <tr>
                ${th}
              </tr>
            </thead>
            <tbody>
              ${tbody}
            </tbody>
          `;
        }

      },
      error: function (jqXHR, errorThrown, textStatus) {
        console.log(textStatus);
      }
    });
  });
}
//end compare product
