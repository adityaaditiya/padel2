</div> <!-- /.container -->

<!-- JS dependencies via CDN -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var phoneInputs = document.querySelectorAll('input[name="no_telepon"], #modal-phone, #customer-phone');
    phoneInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length < 10) {
                this.setCustomValidity('Masukkan minimal 10 digit angka');
            } else {
                this.setCustomValidity('');
            }
        });
    });
});
</script>
</body>
</html>