<script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<script>
    $(function() {
        $("input[data-bootstrap-switch]").each(function() {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        })
    });
</script>
