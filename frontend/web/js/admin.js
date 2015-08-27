(function($){
    $(window).load(function() {

        if($('.redactor-toolbar').length > 0) {
            $('nav.navbar-fixed-top').removeClass('navbar-fixed-top');
            $('.wrap > .container').css('padding-top','10px');
        }

        function saveCoords(c) {
            var x = c.x < 0 ? 0 : c.x/c.imageWidth;
            var y = c.y < 0 ? 0 : c.y/c.imageHeight;

            var x2 = c.x2 < 0 ? 1 : c.x2/c.imageWidth;
            var y2 = c.y2 < 0 ? 1 : c.y2/c.imageHeight;

            var w = c.x < 0 ? x2 : c.x2 < 0 ? 1 - x : c.w/c.imageWidth;
            var h = c.y < 0 ? y2 : c.y2 < 0 ? 1 - y : c.h/c.imageHeight;

            var params = [x,y,w,h];

            $('#crop-data').val(params.join(';'));
        }

        function initJcrop($image, ratio)
        {
            var height = $image.height();
            var width = $image.width();
            var minSize = 100;
            $image.Jcrop({
                aspectRatio: ratio,
                setSelect: [
                    0,
                    0,
                    height,
                    width
                ],
                minSize: [ratio * minSize, minSize],
                onSelect: saveCoords,
                onChange: saveCoords,
            });
        };

        var $inputJcrop = $(".field-post-mainimagefile :file");
        if($inputJcrop.length > 0) 
        {
            var ratio = $inputJcrop.attr('data-crop-ratio');
            ratio = ratio ? ratio : 1;
            $inputJcrop.on('fileimageloaded', function(event, previewId){
                initJcrop($('#' + previewId + ' img'), ratio);
                $('#' + $inputJcrop.attr('id') + '-remove').val(0);
            });
            $(document).on('click', '.field-post-mainimagefile .kv-file-remove', function(event) {
                $('#post-mainimageremove').val(1);
            });
        }

        var $input = $(".field-post-sliderimagefiles .file-input :file");
        $input.on("filepredelete", function(event, key) {
            var imageKeys = $("#post-sliderimagekeys").val();
            if(imageKeys) {
                var keys = imageKeys.split(';');
                var index = keys.indexOf(key.toString());
                if (index > -1) {
                    keys.splice(index, 1);
                }
                $("#post-sliderimagekeys").val(keys.join(';'));
            }
            return false;
        });
        
    });
})(jQuery);