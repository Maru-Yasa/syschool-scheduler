<script>
    toastr.options.closeButton = true;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="my-csrf-token"]').attr('content')
        }
    });

    $.extend(true, $.fn.dataTable.defaults, {
        language:{
            url:"//cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json",
        }
    });
</script>