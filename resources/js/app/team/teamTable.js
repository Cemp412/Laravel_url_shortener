$(document).ready(function() {

    //load team table
    $("#teamTable").DataTable({
    
        ajax: {
            'url': "/api/team",
            type: "GET",
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                'Accept': 'application/json'
            },
            error: function(xhr, error, code) {
                console.error("DataTables Error: ", code);
            },
        },
        columns: [
            { 
                data: 'name', 
                name: 'users.name',
            },
            { 
                data: 'email', 
                name: 'users.email', 
            },
            { 
                data: 'role', 
                name: 'roles.name', 
            },
            {
                data:'total_urls',
                name:'total_urls',
                searchable:false
            },
            { 
                data: 'total_hits', 
                name: 'total_hits', 
                searchable: false 
            },
        ]
    });
    

    //load urls table
    const urlsTable = $("#urlsTable").DataTable({
        ajax: {
            url: "/api/team-generated-urls",
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
            { data: 'short_url', name: 'short_urls.short_code' },
            { data: 'long_url', name: 'long_url' },
            { data: 'hits', name: 'short_urls.hits' },
            { data: 'created_by', name: 'users.name' },
            { data: 'created_at', name: 'short_urls.created_at' }
        ]
    });


    // Reload the table automatically when the filter changes
    $('#dateFilter').on('change', function() {
        urlsTable.ajax.reload();
    });
    
});