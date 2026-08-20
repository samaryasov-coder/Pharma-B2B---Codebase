$(document)
    .off('submit.pb2bFilters', '.js-sidebar-filters-form')
    .on('submit.pb2bFilters', '.js-sidebar-filters-form', function (e) {
        e.preventDefault();

        var qs = $(this).serialize();
        window.location.hash = '#/company//' + qs;
    });
