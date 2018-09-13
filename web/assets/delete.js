$(function () {
    $('body').on('click', '.delete_selected', function () {
        var delete_id = $(this).attr('id');
        var entidad = $(this).attr('entidad');
        var ruta = $(this).attr('ruta');
        bootbox.dialog({
            message: "¿Desea eliminar el/la " + $(this).attr('tipo') + ' ' + $(this).attr('name') + "?",
            title: "Alerta",
            buttons: {
                danger: {
                    label: "Eliminar",
                    className: "btn-danger",
                    callback: function () {
                        var route = Routing.generate('to_delete', {id: 'option', entity: 'model', path_: 'dir'});
                        route = route.replace('option', delete_id);
                        route = route.replace('model', entidad);
                        route = route.replace('dir', ruta);
                        location.href = route;
                    }
                },
                main: {
                    label: "Cancelar",
                    className: "btn-primary",
                    callback: function () {
                        bootbox.hideAll();
                    }
                }
            }
        });
    });
});