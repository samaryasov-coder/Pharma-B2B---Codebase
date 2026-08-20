$(document)
    .off('submit.pb2bTenderFilters', '.js-sidebar-filters-form[data-namespace="tender"]')
    .on('submit.pb2bTenderFilters', '.js-sidebar-filters-form[data-namespace="tender"]', function (e) {
        e.preventDefault();
        window.location.hash = '#/tender//' + $(this).serialize();
    });
