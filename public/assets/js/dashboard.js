$(function () {

    /* ──────────────────────────────────────────────
       1.  SIDEBAR NAVIGATION
    ────────────────────────────────────────────── */
    $('[data-target]').on('click', function () {
        const t = $(this).data('target');
        $('[data-target]').removeClass('active');
        $(this).addClass('active');
        $('.page-section').removeClass('active');
        $('#sec-' + t).addClass('active');
    });


    /* ──────────────────────────────────────────────
       2.  DATATABLES  (jQuery-powered)
    ────────────────────────────────────────────── */
    const datatableUrl   = $('#appConfig').data('datatable-url');

const dt = $('#userTable').DataTable({
    paging:       true,
    lengthChange: true,
    searching:    true,
    serverSide:   true,
    processing:   true,
    ordering:     true,
    info:         true,
    autoWidth:    false,
    responsive:   true,
    pageLength:   10,
    order:        [[0, 'asc']],

    ajax: {
        url:  datatableUrl,
        type: 'POST',
        data: function (d) {
            d[csrfTokenName] = csrfHash;
            d.dateFrom       = $('#dateFrom').val();
            d.dateTo         = $('#dateTo').val();
        },
        dataSrc: function (json) {
            // Refresh CSRF hash for next request
            if (json.csrfHash) csrfHash = json.csrfHash;
            return json.data;
        }
    },

    columns: [
        { data: 'id',           searchable: false },
        { data: 'username' },
        { data: 'email',        searchable: false },  // encrypted — skip search
        { data: 'gender' },
        { data: 'phone_number' },
        { data: 'user_type' },
        { data: 'created_at',   searchable: false },
        { data: 'actions',      orderable: false, searchable: false }
    ],

    language: {
        search:            '<i class="fas fa-search"></i>',
        searchPlaceholder: 'Search users...',
        lengthMenu:        'Show _MENU_ entries',
        info:              'Showing _START_–_END_ of _TOTAL_ users',
        processing:        '<i class="fas fa-spinner fa-spin me-1"></i> Loading...',
        paginate:          { previous: '‹', next: '›' },
        zeroRecords:       'No users found',
        emptyTable:        'No users available'
    }


});

    /* ──────────────────────────────────────────────
       3.  DATE FILTER  (filters Registered column)
    ────────────────────────────────────────────── */
    /* ──────────────────────────────────────────────
   DATE RANGE FILTER
────────────────────────────────────────────── */

// Register custom range search against the data-order timestamp on col 6
$.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    const from = $('#dateFrom').val();
    const to   = $('#dateTo').val();

    // If both inputs are empty, show all rows
    if (!from && !to) return true;

    // Read the Unix timestamp stored in data-order on the <td>
    const rowTs  = parseInt(
        $(dt.row(dataIndex).node()).find('td:eq(6)').attr('data-order')
    );

    const fromTs = from ? new Date(from + 'T00:00:00').getTime() / 1000 : null;
    const toTs   = to   ? new Date(to   + 'T23:59:59').getTime() / 1000 : null;

    if (fromTs && rowTs < fromTs) return false;
    if (toTs   && rowTs > toTs)   return false;
    return true;
});

// Trigger redraw whenever either input changes
$('#dateFrom, #dateTo').on('change', function () {
    // Prevent from > to
    const from = $('#dateFrom').val();
    const to   = $('#dateTo').val();
    if (from && to && from > to) {
        alert('Start date cannot be after end date.');
        $(this).val('');
        return;
    }
    dt.draw();
});

// Clear button resets both inputs
$('#clearDates').on('click', function () {
    $('#dateFrom, #dateTo').val('');
    dt.draw();
});


    /* ──────────────────────────────────────────────
       4.  CSV EXPORT  (client-side, all filtered rows)
    ────────────────────────────────────────────── */
    $('#btnCSV').on('click', function () {
        const strip = html => $('<div>').html(html).text().trim();
        const rows  = [['#','Username','Email','Gender','Mobile','user_type']];

        dt.rows({ search: 'applied' }).every(function () {
            const d = this.data();
            rows.push([
                strip(d[0]), strip(d[1]), strip(d[2]),
                strip(d[3]), strip(d[4]), strip(d[5]), strip(d[6])
            ]);
        });

        const csv  = rows.map(r => r.map(c => '"' + String(c).replace(/"/g,'""') + '"').join(',')).join('\n');
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url  = URL.createObjectURL(blob);
        const a    = Object.assign(document.createElement('a'), {
            href: url, download: 'users_' + new Date().toISOString().slice(0,10) + '.csv'
        });
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    });


    /* ──────────────────────────────────────────────
       5.  PDF EXPORT  (client-side via jsPDF)
    ────────────────────────────────────────────── */
    $('#btnPDF').on('click', function () {
        const { jsPDF } = window.jspdf;
        const doc  = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
        const strip = html => $('<div>').html(html).text().trim();

        // Header text
        doc.setFontSize(15);
        doc.setTextColor(53, 88, 114);
        doc.text('User Directory', 14, 15);
        doc.setFontSize(9);
        doc.setTextColor(140, 140, 140);
        doc.text('Exported: ' + new Date().toLocaleDateString('en-US',{day:'numeric',month:'long',year:'numeric'}), 14, 21);

        const head = [['#','Username','Email','Gender','Mobile','user_type']];
        const body = [];
        dt.rows({ search: 'applied' }).every(function () {
            const d = this.data();
            body.push([
                strip(d[0]), strip(d[1]), strip(d[2]),
                strip(d[3]), strip(d[4]), strip(d[5]), strip(d[6])
            ]);
        });

        doc.autoTable({
            head, body,
            startY: 25,
            headStyles: {
                fillColor:  [156, 213, 255],
                textColor:  [53, 88, 114],
                fontStyle:  'bold',
                fontSize:   9.5
            },
            bodyStyles:           { fontSize: 9, textColor: [55, 55, 55] },
            alternateRowStyles:   { fillColor: [247, 248, 240] },
            margin:               { left: 14, right: 14 }
        });

        doc.save('users_' + new Date().toISOString().slice(0,10) + '.pdf');
    });


    /* ──────────────────────────────────────────────
       6.  EDIT MODAL — pre-fill fields from data-*
    ────────────────────────────────────────────── */
    // Read base URLs from the hidden config element
    const updateBaseUrl = $('#appConfig').data('update-url');
   $('#editModal').on('show.bs.modal', function (e) {
    const b = $(e.relatedTarget);
    $('#eUsername').val(b.data('username'));
    $('#ePhone').val(b.data('phone'));
    $('#eGender').val(b.data('gender'));

    // Use the URL read from data attribute — no PHP tag needed
    $('#editForm').attr('action', updateBaseUrl + '/' + b.data('id'));
});

  /* ──────────────────────────────────────────────
   PROFILE IMAGE PREVIEW
────────────────────────────────────────────── */
$('#profileImageInput').on('change', function () {
    const file = this.files[0];
    if (!file) return;

    // Validate size (2MB max)
    // if (file.size > 2 * 1024 * 1024) {
    //     alert('Image is too large. Maximum size is 2MB.');
    //     this.value = '';
    //     return;
    // }

    const reader = new FileReader();
    reader.onload = function (e) {
        // Replace preview with selected image
        $('#profilePreview').replaceWith(
            '<img id="profilePreview" src="' + e.target.result + '" ' +
            'style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid var(--accent);">'
        );
    };
    reader.readAsDataURL(file);
});


    /* ──────────────────────────────────────────────
       7.  DELETE MODAL — confirm + set action
    ────────────────────────────────────────────── */
const deleteBaseUrl = $('#appConfig').data('delete-url');
$(document).on('click', '.btn-delete-trigger', function () {
    const userId   = $(this).data('id');
    const username = $(this).data('username');

    $('#dUsername').text(username);
    $('#deleteUserId').val(userId);

    // Use the URL read from data attribute — no PHP tag needed
    $('#deleteForm').attr('action', deleteBaseUrl + '/' + userId);

    $('#deleteModal').modal('show');
});

    /* ──────────────────────────────────────────────
       8.  AUTO-DISMISS flash alerts after 4s
    ────────────────────────────────────────────── */
// ── Profile image — validate before submit, show toast not browser popup ──
document.getElementById('profileImageInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;

    const maxSize = 2 * 1024 * 1024;
    const allowed = ['image/jpeg', 'image/png', 'image/webp'];

    if (!allowed.includes(file.type)) {
        showToast('Only JPG, PNG or WEBP images are allowed.', 'error');
        this.value = '';
        return;
    }

    if (file.size > maxSize) {
        showToast('Image is too large. Maximum size is 2MB.', 'error');
        this.value = '';
        return;
    }

    // Live preview in the modal
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('profilePreview');
        if (preview.tagName === 'IMG') {
            preview.src = e.target.result;
        } else {
            // Replace the icon div with an img tag
            const img = document.createElement('img');
            img.id = 'profilePreview';
            img.src = e.target.result;
            img.style = 'width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid var(--accent);';
            preview.replaceWith(img);
        }
    };
    reader.readAsDataURL(file);
});

// ── Toast helper ──────────────────────────────────────────────────────────
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const div = document.createElement('div');
    div.className = `flash-alert alert-${type === 'error' ? 'danger' : 'success'}`;
    div.innerHTML = `<i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'}"></i><span>${message}</span>`;
    container.appendChild(div);
    setTimeout(() => div.remove(), 4000);
}


    $(document).ready(function() {
    // Select all flash alerts
    const $alerts = $('.flash-alert');

    if ($alerts.length > 0) {
        // Wait 4 seconds, then fade out and remove from DOM
        setTimeout(() => {
            $alerts.fadeOut(400, function() {
                $(this).remove(); 
            });
        }, 4000);
    }
});

});

