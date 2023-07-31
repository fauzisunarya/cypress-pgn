/**
* @file
*/

(function ($, Drupal) {
Drupal.AjaxCommands.prototype.previewContent = function (ajax, response, status) {
  if (response.args && response.args.url) {
    if (!$('#live-preview').length) {
      $('body').append(
        `<div id="live-preview" class="live-preview">
          <div class="live-preview__actions">
            <button id="live-preview-btn-close" class="live-preview__actions__btn-close">Close</button>
            <button id="live-preview-btn-fullscreen" class="live-preview__actions__btn-fullscreen">Fullscreen</button>
          </div>
          <iframe id="live-preview-iframe" class="live-preview__iframe" src="${response.args.url}" width="100%" height="100%" frameborder="0" allowfullscreen=""></iframe>
        </div>`
      ).animate({
        transform: 'translate(0, 0)'
      }, 'slow', 'linear');

      $('body').addClass('live-preview-active');
      $('.live-preview').addClass('live-preview--open');
    } else {
      $('#live-preview-iframe').attr("src", function(index, attr){ 
        return attr;
      });
    }

    // Live preview close event.
    $('#live-preview-btn-close').once().on('click', function() {
      $('#live-preview').animate({
        transform: 'translate(100%, 0)'
      }, 'slow', 'linear', function() {
        $(this).remove()
      })
      $('body').removeClass('live-preview-active');
      $('.live-preview').removeClass('live-preview--open');
    })

    // Live preview close fullscreen.
    $('#live-preview-btn-fullscreen').once().on('click', function() {
      let el = $(this);
      el.text(function() {
        return (el.text() == "Fullscreen") ? "Exit Fullscreen" : "Fullscreen";
      });
      $('.live-preview').toggleClass('live-preview--fullscreen');
    });
  }
}
})(jQuery, Drupal);
