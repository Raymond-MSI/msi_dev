jQuery(document).ready(function($) {

    var at_document = $(document);

    at_document.on('click','.custom_intimate_button', function(e){

        // Prevents the default action from occuring.
        e.preventDefault();
        var intimate_image_upload = $(this);
        var intimate_title = $(this).data('title');
        var intimate_button = $(this).data('button');
        var intimate_input_val = $(this).prev();
        var intimate_image_url_value = $(this).prev().prev().children('img');
        var intimate_image_url = $(this).siblings('.img-preview-wrap');

        var meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
            title: intimate_title,
            button: { text:  intimate_button },
            library: { type: 'image' }
        });
        // Opens the media library frame.
        meta_image_frame.open();
        // Runs when an image is selected.
        meta_image_frame.on('select', function(){

            // Grabs the attachment selection and creates a JSON representation of the model.
            var intimate_attachment = meta_image_frame.state().get('selection').first().toJSON();

            // Sends the attachment URL to our custom image input field.
            intimate_input_val.val(intimate_attachment.url);
            if( intimate_image_url_value !== null ){
                intimate_image_url_value.attr( 'src', intimate_attachment.url );
                intimate_image_url.show();
                LATESTVALUE(intimate_image_upload.closest("p"));
            }
        });
    });

   // Runs when the image button is clicked.
   jQuery('body').on('click','.intimate-image-remove', function(e){
    $(this).siblings('.img-preview-wrap').hide();
    $(this).prev().prev().val('');
});
   
   var LATESTVALUE = function (wrapObject) {
    wrapObject.find('[name]').each(function(){
        $(this).trigger('change');
    });
};  

});
