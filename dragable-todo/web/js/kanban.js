function initKanban(updateStatusUrl, csrfToken) {
    function initSortable() {
        $(".kanban-column").sortable({
            connectWith: ".kanban-column",
            placeholder: "ui-state-highlight",
            items: ".kanban-task",
            receive: function(event, ui) {
                var itemId = ui.item.data('id');
                var newStatus = $(this).attr('id');

                $.ajax({
                    url: updateStatusUrl,
                    type: 'POST',
                    data: {
                        id: itemId,
                        status: newStatus,
                        _csrf: csrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log('Status updated for task ID ' + itemId + ' to ' + newStatus);
                            updateTaskCounts();
                        } else {
                            console.error('Failed to update status:', response.error);
                            alert('Failed to update status: ' + response.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', status, error, xhr.responseText);
                        alert('Failed to update status: ' + error);
                    }
                });
            }
        }).disableSelection();
    }

    function updateTaskCounts() {
        $('.task-count').each(function() {
            var status = $(this).data('status');
            var count = $('#' + status).find('.kanban-task').length;
            $(this).text(count);
        });
    }

    $(function() {
        initSortable();
    });
}
