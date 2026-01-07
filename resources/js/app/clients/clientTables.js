$(document).ready(function() {

    //load client table
    $("#clientsTable").DataTable({
        // processing:true, //loader
        // serverSide:true,
        // deferender:true,
        // responsive:true,
        ajax: {
            'url': "/api/clients",
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
                name: 'name',
                render: function(data, type, row) {
                    return `<div class="fw-bold">${data}</div><small class="text-muted" style="font-size: 0.85em;">${row.email}</small>`;
                },
            },
            {
                data: 'status',
                name: 'status',
                render: function(data, type, row) {
                    if(row.status == 'active') {
                        return `<span class="badge rounded-pill bg-success p-2">Active</span>`;
                    }
                    else{
                        return `<span class="badge rounded-pill bg-danger p-2">In-active</span>`;
                    }
                }
            },
            { 
                data: 'users_count', 
                name: 'users_count', 
                searchable: false 
            },
            { 
                data: 'short_urls_count', 
                name: 'short_urls_count', 
                searchable: false 
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
            url: "/api/generated-urls",
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                'Accept': 'application/json'
            },
            data: function (d) {
                d.filter = $('#urlFilterDropdown').val(); 
            }
        },
        columns: [
           /*  { 
                data: 'DT_RowIndex', 
                name: 'DT_RowIndex', 
                orderable: false, 
                searchable: false 
            }, */
            { 
                data: 'short_url',
                name: 'short_url' 
            },
            { 
                data: 'original_url', 
                name: 'original_url' 
            },
            {
                data: 'hits',
                name: 'hits'
            },
            { 
                data: 'company', 
                name: 'company' 
            },
            { 
                data: 'created_at', 
                name: 'created_at',
                render: function(data) {
                    return new Date(data).toLocaleDateString('en-GB', {
                        day: '2-digit', month: 'short', year: 'numeric'
                    });
                }
            }
        ]
    });

    // Reload the table automatically when the filter changes
    $('#dateFilter').on('change', function() {
        urlsTable.ajax.reload();
    });
    
});