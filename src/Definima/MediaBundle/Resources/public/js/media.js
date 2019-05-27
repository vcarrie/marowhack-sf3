$(function () {
    $(document).ready(function () {
        var $document = $(document);
        var $definimaPath;
        var $definimaTarget;

        $document
            .on('click', '.definima-media-button-erase', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var $definimaMedia = $(this).closest('.definima-media');
                var $imagePath = $definimaMedia.find('.definima-media-path');

                $imagePath.val('');
                $imagePath.change();
                $definimaMedia.find('.img-preview').html('');
            })
            .on('change', '.definima-media-path', function (e) {
                var path = $(this).val();
                var id = $(this).attr('id');
                var conf = $(this).data('conf');
                var nom = $(this).data('nom');
                updatePreview(conf, path, nom, $('#preview' + id));
            });

        $('.definima-media-collection').each(function () {
            var $this = $(this);
            $this.collection({
                max: $this.data('max'),
                min: $this.data('min'),
                init_with_n_elements: $this.data('init-with-n-elements'),
                add: '<span class="images-add"><a href="#" class="btn btn-default"><span class="fa fa-plus"></span> ' + addStr + '</a></span>',
                add_at_the_end: $this.data('add-at-the-end'),
                after_add: function (collection, element) {
                    initFileUpload(element.find('.fileupload'));
                    return true;
                },
                before_remove: function (collection, element) {
                    $(element.find('.fileupload')).fileupload('destroy');
                    return true;
                }
            });
        });

        var media = '.definima-media';

        $document.on('dragover dragenter', media, function () {
            $(this).addClass('is-dragover');
        })
            .on('dragleave dragend drop', media, function () {
                $(this).removeClass('is-dragover');
            });

        // filemanager
        initFileUpload('.fileupload.alone');
        initFileUpload('.ui-sortable-handle .fileupload');
    });
});

function updatePreview(conf, path, nom, dest) {
    $.ajax({
        url: url,
        data: {'path': path, 'conf': conf, 'nom': nom},
        type: 'GET',
        success: function (res) {
            dest.html(res.icon.html);
        },
        error: function () {
            alert('Une erreur est survenue');
        }
    });
}

function initFileUpload(selector) {
    $(selector).each(function () {
        $(this).fileupload({
            dataType: 'json',
            processQueue: false,
            dropZone: $(this).closest('.definima-media')
        }).on('fileuploaddone', function (e, data) {

            var $elem = $(e.currentTarget).closest('.fileinput-button')
            $elem.find('.gear-upload').addClass('d-none');
            $elem.find('.fa-folder-open').removeClass('d-none');

            $.each(data.result.files, function (index, file) {
                if (file.url) {


                    // Ajax update view
                    $.ajax({
                        dataType: "json",
                        url: url,
                        data: {'path': file.url, 'conf': file.conf, 'nom': file.nom},
                        type: 'GET'
                    }).done(function (data) {
                        displaySuccess("Votre fichier " + successMessage);
                        var $input = $(e.target).closest('.definima-media').find('input.definima-media-path');
                        $input.val(file.url);
                        $input.change();
                        if ($(e.target).closest('.fileupload').prop('required'))
                            $(e.target).closest('.fileupload').prop('required', false);
                        //update preview
                        //updatePreview(file.conf, file.url, file.nom, $('#preview' + $input.attr('id')));

                        // update iframe
                        $('.iframe').attr('src', function (i, val) {
                            return val;
                        });
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        displayError('<strong>Ajax call error :</strong> ' + jqXHR.status + ' ' + errorThrown)
                    });

                } else if (file.error) {
                    displayError(file.error)
                }
            });
        }).on('fileuploadfail', function (e, data) {
            var $elem = $(e.currentTarget).closest('.fileinput-button')
            $elem.find('.gear-upload').addClass('d-none');
            $elem.find('.fa-folder-open').removeClass('d-none');

            $.each(data.files, function (index) {
                displayError('File upload failed.')
            });
        }).on('fileuploadsend', function (e, data) {
            var $elem = $(e.currentTarget).closest('.fileinput-button')
            $elem.find('.gear-upload').removeClass('d-none');
            $elem.find('.fa-folder-open').addClass('d-none');
        });
    });

}
