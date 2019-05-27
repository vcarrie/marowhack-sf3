$(document).ready(function () {

    $('.DataTable').DataTable({
        stateSave: true,
        searching: true,
        responsive: false,
        info: false,
        columnDefs: [{
                "targets": 'no-sort',
                "orderable": false,
            }],
        fnDrawCallback: function () {
            var $paginate = $('.dataTables_paginate');
            $paginate.css('margin', '15px 30px');
            $('.dataTables_length').css('margin', '15px 0px');
            $('#DataTables_Table_0_filter').css('float', 'left').css('margin', '15px 0px');
            $('#DataTables_Table_0_filter input').css('width', '250px');

            if (this.api().data().length <= this.fnSettings()._iDisplayLength) {
                $paginate.hide();
            } else {
                $paginate.show();
            }

        },
        iDisplayLength: 50,
        language: {
            processing: "Traitement en cours...",
            search: "Rechercher&nbsp;:",
            lengthMenu: "Afficher :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_MENU_&nbsp;&nbsp;&eacute;l&eacute;ments",
            info: "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            infoEmpty: "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
            infoFiltered: "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            infoPostFix: "",
            loadingRecords: "Chargement en cours...",
            zeroRecords: "Aucun &eacute;l&eacute;ment &agrave; afficher",
            emptyTable: "Aucune donn&eacute;e disponible dans le tableau",
            paginate: {
                first: "Premier",
                previous: "Pr&eacute;c&eacute;dent",
                next: "Suivant",
                last: "Dernier"
            },
            aria: {
                sortAscending: ": activer pour trier la colonne par ordre croissant",
                sortDescending: ": activer pour trier la colonne par ordre d&eacute;croissant"
            }
        },
    });

    $(document).on('click', '.deluser', function (e) {
        e.preventDefault();

        $id = $(this).data('id');
        $action = $('#delModal').data('url');
        $action = $action.replace("-id-", $id);
        $('#delModal form').attr('action', $action);
        $('#delModal').modal('show');
    });

    $(document).on('click', '.supprimer', function (e) {
        $('#delModal form').submit();
    });

    $('.switch').bootstrapSwitch({
        size: "mini",
        onText: "Oui",
        offText: "Non"
    });
    
    if ($('.list-admins').length) {
        var table = $(".records_list").DataTable({
            stateSave: true,
            searching: true,
            responsive: false,
            info: false,
            columnDefs: [{
                    "targets": 'no-sort',
                    "orderable": false,
                }],
            fnDrawCallback: function () {
                var $paginate = $('.dataTables_paginate');
                $paginate.css('margin', '15px 30px');

                if (this.api().data().length <= this.fnSettings()._iDisplayLength) {
                    $paginate.hide();
                } else {
                    $paginate.show();
                }

            },
            "initComplete": function (settings, json) {
                $('.cont-liste .well').append($('#DataTables_Table_0_wrapper .row').first());
            },
            iDisplayLength: 50,
            language: {
                processing: "Traitement en cours...",
                search: "Rechercher&nbsp;:",
                lengthMenu: "Afficher :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_MENU_&nbsp;&nbsp;&eacute;l&eacute;ments",
                info: "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                infoEmpty: "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
                infoFiltered: "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                infoPostFix: "",
                loadingRecords: "Chargement en cours...",
                zeroRecords: "Aucun &eacute;l&eacute;ment &agrave; afficher",
                emptyTable: "Aucune donn&eacute;e disponible dans le tableau",
                paginate: {
                    first: "Premier",
                    previous: "Pr&eacute;c&eacute;dent",
                    next: "Suivant",
                    last: "Dernier"
                },
                aria: {
                    sortAscending: ": activer pour trier la colonne par ordre croissant",
                    sortDescending: ": activer pour trier la colonne par ordre d&eacute;croissant"
                }
            }
        });
    }
});
