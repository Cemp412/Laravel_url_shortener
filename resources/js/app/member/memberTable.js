$(document).ready(function() {
    //load urls table
    const urlsTable = $("#urlsTable").DataTable({
        
        ajax: {
            url: "/api/short-urls-list",
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                'Accept': 'application/json'
            },
            error: function(xhr, error, code) {
                console.error("DataTables Error: ", code);
            },
            data: function (d) {
                d.filter = $('#dateFilter').val();
            }
        },
        columns: [
            { data: 'short_url', name: 'short_code' },
            { data: 'long_url', name: 'long_url' },
            { data: 'hits', name: 'hits' },
            { data: 'created_at', name: 'created_at' }
        ]
    });


    // Reload the table automatically when the filter changes
    $('#dateFilter').on('change', function() {
        urlsTable.ajax.reload();
    });
});